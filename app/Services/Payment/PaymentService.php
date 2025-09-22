<?php
namespace App\Services\Payment;

use App\Models\Payment;
use Exception;

class PaymentService{
    public function update(Payment $payment , array $data){
        try{
            $payment->update($data);
            return [
                'status'=>'success',
                'data'=>$payment
            ];
        }catch(Exception $e){
            return [
                'status'=>'failed',
                'message'=>$e->getMessage()
            ];
        }
    }
}
