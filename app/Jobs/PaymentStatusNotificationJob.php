<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PaymentStatusNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $paymentId;
    public $agentId ;
    public function __construct($paymentId , $agentId)
    {
        $this->paymentId = $paymentId ;
        $this->agentId = $agentId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = Payment::findOrFail($this->paymentId);
        $agent = User::findOrFail($this->agentId);

        $agent->notify(new PaymentStatusNotification($payment));
    }
}
