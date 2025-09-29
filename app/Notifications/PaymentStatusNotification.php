<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $payment;
    public function __construct($payment)
    {
        $this->payment = $payment ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Status Update')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your payment of ' . $this->payment->amount . ' using ' . $this->payment->method . ' has been processed.')
            ->line('Payment Status: ' . ucfirst($this->payment->status))
            ->line('Date: ' . Carbon::parse($this->payment->date)->toDateTimeString())
            ->action('View Details', url('/payments/' . $this->payment->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'status' => $this->payment->status,
            'method' => $this->payment->method,
            'date' => $this->payment->date,
        ];
    }
}
