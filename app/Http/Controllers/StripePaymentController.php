<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\StripePaymentRequest;
use App\Services\Payment\StripePaymentService;
use Exception;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripePaymentController extends Controller
{
    protected $service ;
    public function __construct(StripePaymentService $service){
        $this->service = $service ;
    }
    public function createPaymentIntent($type ,$id ,StripePaymentRequest $request){
        $info = $this->service->createIntent( $type ,$id ,$request->validated()['user_id'] ?? null);
        return $info['status'] == 'success' ?
            self::success([$info['data']]) :
            self::error('Error Occurred' ,'error' ,$info['code'] ,[$info['message']]);
    }
    public function handleWebhook(Request $request){
        $message = $this->service->handleWebhook($request);
        return response()->json([
            'message'=>$message
        ]);
    }
}
