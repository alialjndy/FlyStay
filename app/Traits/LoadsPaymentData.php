<?php

namespace App\Traits;

use App\Models\Payment;
use Carbon\Carbon;

trait LoadsPaymentData
{
    protected function loadPaymentData($paymentId){
        $payment = Payment::with([
            'user',
        ])->findOrFail($paymentId);

        return $payment ;
    }

    //
    protected function transformPaymentData($payment){
        $paymentData = [
            'payment_amount' => $payment->amount,
            'payment_date'   => Carbon::parse($payment->date),
            'payment_method' => $payment->method,
            'payment_status' => $payment->status,
        ];
        return $paymentData ;
    }
}
