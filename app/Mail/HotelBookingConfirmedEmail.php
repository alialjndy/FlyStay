<?php

namespace App\Mail;

use App\Traits\LoadsHotelBookingData;
use App\Traits\LoadsPaymentData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class HotelBookingConfirmedEmail extends Mailable
{
    use Queueable, SerializesModels , LoadsHotelBookingData , LoadsPaymentData;

    /**
     * Create a new message instance.
     */
    protected $bookingId ;
    protected $paymentId ;
    public function __construct($bookingId , $paymentId)
    {
        $this->bookingId = $bookingId ;
        $this->paymentId = $paymentId ;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@FlyStay.com','FlyStay'),
            replyTo:[
                new Address('Support@FlyStay.com','Support')
            ],
            subject: 'Hotel Booking Confirmed Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $booking = $this->loadBookingData($this->bookingId);
        $payment = $this->loadPaymentData($this->paymentId);

        $bookingData = $this->transformBookingData($booking);
        $paymentData = $this->transformPaymentData($payment);
        return new Content(
            view: 'emails.hotel-booking-confirmed',
            with:[
                'booking_data'=>$bookingData ,
                'payment_data'=>$paymentData
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
