<?php
namespace App\Services\Payment;

use App\Jobs\FlightBookingConfirmedEmailJob;
use App\Jobs\HotelBookingConfirmedEmailJob;
use App\Jobs\PaymentStatusNotificationJob;
use App\Models\FlightBooking;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentStatusNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;
use Tymon\JWTAuth\Facades\JWTAuth;

class StripePaymentService {
    public function __construct() {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    private function getMessage($status , $message ,$code = 400) {
        return [
            'status'=> $status ,
            'message' => $message,
            'code'=> $code
        ];
    }

    private function getAuthenticatedUser() {
        return JWTAuth::parseToken()->authenticate();
    }
    public function createIntent($model ,$id ,$agentUserId = null) {

        // Get Model Class
        $modelClass = match($model) {
            'flight-booking' => FlightBooking::class,
            'hotel-booking' => HotelBooking::class,
            default => throw new \InvalidArgumentException('Model not supported'),
        };

        // Find the booking instance
        $modelInstance = $modelClass::find($id); // eg: FlightBooking::find(1)
        if (!$modelInstance) {
            return $this->getMessage('error','Model instance not found.', 404);
        }

        try {
            // Get authenticated user and check their role
            $authUser = $this->getAuthenticatedUser();
            $isCustomer = $authUser->hasRole('customer');

            // Verify user has proper authorization
            if(!$isCustomer && !$authUser->hasAnyRole(['flight_agent','hotel_agent'])){
                return $this->getMessage('error', 'Unauthorized payment agent.', 403);
            }

            // Set payment parameters based on user role
            $userId     = $isCustomer ? $authUser->id : $agentUserId;
            $method     = $isCustomer ? 'stripe' : 'cash';
            $verifiedBy = $isCustomer ? null : $authUser->id ;
            $status     = $isCustomer ? 'pending' : 'completed';
            $clientSecret = null ;
            $transactionId = null;

            // Verify user owns the booking
            if($userId != $modelInstance->user_id){
                return $this->getMessage('error','User is not the owner of this booking.', 403);
            }


            // Handle Stripe payments (customer scenario)
            if($isCustomer){

                // Check if there is already an existing payment record for this user and booking
                // If found and its status is "pending", stop and return an error response
                $existingPayment = $authUser->activePayment($modelClass , $id);
                if($existingPayment){
                    return $this->getMessage('failed','payment record already exists but with status pending',403);
                }

                // Convert amount from dollars to cents
                $amountInCents = (int) ($modelInstance->getAmount() * 100);

                // Create a new PaymentIntent on Stripe
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amountInCents,
                    'currency' => 'usd',
                ]);
                $transactionId = $paymentIntent->id ;
                $clientSecret = $paymentIntent->client_secret ;
            }

            $paymentInfo = $modelInstance->payments()->create([
                'user_id' => $userId,
                'method' => $method,
                'transaction_id' => $transactionId,
                'verified_by' => $verifiedBy,
                'amount' => $modelInstance->getAmount(),
                'date' => Carbon::now(),
                'status' => $status,
            ]);

            // Handle cash payments (agent scenario)
            if($method == 'cash'){
                $modelInstance->status = 'confirmed';
                $modelInstance->save();

                $booking = $paymentInfo->payable ;
                $agentId = $paymentInfo->verified_by;
                $agent = User::findOrFail($agentId);

                if($booking instanceof FlightBooking){
                    dispatch(new PaymentStatusNotificationJob($paymentInfo->id , $agentId));
                    dispatch(new FlightBookingConfirmedEmailJob($paymentInfo->id , $booking->id));
                }elseif($booking instanceof HotelBooking){
                    dispatch(new PaymentStatusNotificationJob($paymentInfo->id , $agent->id));
                    dispatch(new HotelBookingConfirmedEmailJob($paymentInfo->id , $booking->id));
                }
            }

            return [
                'status' => 'success',
                'data' => $isCustomer ? $clientSecret : $paymentInfo,
            ];
        } catch(Exception $e) {
            return $this->getMessage('failed', $e->getMessage(), 500);
        }
    }

    public function handleWebhook(Request $request) {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            return response()->json($this->getMessage('error', 'Invalid payload', 400), 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json($this->getMessage('error', 'Invalid signature', 400), 400);
        }

        Log::info('Received Stripe Webhook', ['event_type' => $event->type]);

        try {
            switch ($event->type) {
                case 'payment_intent.created':
                    Log::info('PaymentIntent created', ['id' => $event->data->object->id]);
                    break;
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

                    if ($payment) {
                        $payment->status = 'completed';
                        $payment->save();

                        $booking = $payment->payable;
                        if ($booking instanceof Model) {
                            $booking->status = 'confirmed';
                            $booking->save();

                            $classBaseName = class_basename($booking);
                            if($classBaseName === 'FlightBooking'){
                                dispatch(new FlightBookingConfirmedEmailJob($payment->id , $booking->id));
                            }elseif($classBaseName === 'HotelBooking'){
                                dispatch(new HotelBookingConfirmedEmailJob($payment->id , $booking->id));
                            }
                        }
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

                    if ($payment) {
                        $payment->status = 'failed';
                        $payment->transaction_id = $paymentIntent->id;
                        $payment->save();

                        $booking = $payment->payable;
                        if ($booking instanceof Model) {
                            $booking->status = 'failed';
                            $booking->save();
                        }
                    }
                    break;
                case 'charge.refunded':
                case 'refund.created':
                case 'refund.updated':

                    // Refund Money After Cancelled booking.
                    $charge = $event->data->object ;
                    $payment = Payment::where('transaction_id', $charge->payment_intent)->first();
                    if($payment){
                        $payment->update(['status'=>'refunded']);
                    }
                    break ;
                default:
                    Log::warning('Unhandled Stripe event type', ['event_type' => $event->type]);
                    break;
            }

            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            Log::error('Error handling Stripe webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Webhook handling failed'], 500);
        }
    }
    private function handleEvent($payment , $new_payment_status , $new_booking_status , $payment_Intent){
        $payment->update([
            'status'=>$new_payment_status
        ]);

        $booking = $payment->payable ;
        $booking->update(['status'=>$new_booking_status]);
    }
}
