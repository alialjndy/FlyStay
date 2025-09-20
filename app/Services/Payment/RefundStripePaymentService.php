<?php
namespace App\Services\Payment;

use App\Models\FlightBooking;
use App\Models\HotelBooking;
use Exception;
use Stripe\Refund;
use Stripe\Stripe;

class RefundStripePaymentService{
    public function refunde(HotelBooking | FlightBooking $booking){
        try{
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentInfo = $booking->payment;

            // Create a refund request through Stripe
            $refund = Refund::create([
                'payment_intent'=>$paymentInfo->transaction_id ,
            ]);

            // // Update payment status only if refund succeeded
            // if($refund->status === 'succeeded'){
            //     $paymentInfo->update(['status'=>'refunded']);
            // }
            return [
                'status'=>'success',
                'message'=>'payment refunde successfully',
                'data'=>$refund ,
                'code'=>200
            ];
        }catch(Exception $e){
            return [
                'status'=>'failed',
                'message'=>$e->getMessage() ,
                'code'=>400
            ];
        }
    }
}
