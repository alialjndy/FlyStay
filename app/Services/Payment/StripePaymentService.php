<?php
namespace App\Services\Payment;

use App\Models\FlightBooking;
use App\Models\HotelBooking;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;
use Tymon\JWTAuth\Facades\JWTAuth;

class StripePaymentService{
    public function __construct(){
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    private function getAuthenticatedUser(){
        return JWTAuth::parseToken()->authenticate();
    }
    public function createIntent($model , $id){
        $modelClass = match($model) {
            'flight-booking' => \App\Models\FlightBooking::class,
            'hotel-booking' => \App\Models\HotelBooking::class,
            default => null,
        };
        if(!$modelClass){
            return [
                'status'=>'error',
                'message'=>'Model not supported'
            ];
        }
        $modelInstance = $modelClass::find($id);
        if (!$modelInstance) {
            return [
                'status'=>'error',
                'message'=>'Model instance not found'
            ];
        }
        try{
            $amount = (int) ($modelInstance->getAmount() * 100);
            $paymentIntent = PaymentIntent::create([
                'amount'=>$amount,
                'currency'=>'usd'
            ]);

            $modelInstance->payments()->create([
                'user_id' => $this->getAuthenticatedUser()->id,
                'method' => 'stripe',
                'transaction_id'=>$paymentIntent->id,
                'amount' => $modelInstance->getAmount(),
                'date'=>Carbon::now(),
                'status' => 'pending',
            ]);

            return [
                'status'=>'success',
                'data'=>$paymentIntent->client_secret
            ];
        }catch(Exception $e){
            return [
                'status'=>'failed',
                'message'=>$e->getMessage()
            ];
        }
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return array{code: int, message: string, status: string|string[]}
     */
    public function handleWebhook(Request $request){
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(  $payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return [
                'status'=>'error',
                'message'=>'Invalid payload',
                'code'=>400
            ];
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return [
                'status'=>'error',
                'message'=>'Invalid signature',
                'code'=>400
            ];
        }
        // $paymentIntent = $event->data->object;
        // $status = $event->type === 'payment_intent.succeeded' ? 'completed' : 'failed';
        // $payment = Payment::where('transaction_id',$paymentIntent->id)->first();

        // $payment->status = $status ;
        // $payment->save();

        // $booking = $payment->payable ;
        // $booking->status = $status === 'completed' ? 'complete' : 'failed';
        // $booking->save();

        Log::info('Received Stripe Webhook', ['event_type' => $event->type]);
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;

                $payment = Payment::where('transaction_id',$paymentIntent->id)->first();
                $payment->status = 'completed';
                $payment->save();

                $booking = $payment->payable ;
                $booking->status = 'complete';
                $booking->save();
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;

                $payment = Payment::where('transaction_id',$paymentIntent->id)->first();
                $payment->status = 'failed';
                $payment->transaction_id = $paymentIntent->id;

                $payment->save();

                $booking = $payment->payable ;
                $booking->status = 'failed';
                $booking->save();
                break;
            // ... handle other event types
            default:
                return [
                    'status'=>'error',
                    'message' => 'Received unknown event type' ,
                ];
        }

    }
}
