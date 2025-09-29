<?php

namespace App\Mail;

use App\Traits\LoadsHotelBookingData;
use App\Traits\LoadsPaymentData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class HotelBookingCancelledEmail extends Mailable
{
    use Queueable, SerializesModels , LoadsHotelBookingData , LoadsPaymentData;

    /**
     * Create a new message instance.
     */
    protected $hotelBookingId ;
    public function __construct($hotelBookingId)
    {
        $this->hotelBookingId = $hotelBookingId ;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@FlyStay.com', 'FlyStay'),
            replyTo: [
                new Address('Support@FlyStay.com', 'Support')
            ],
            subject: 'Hotel Booking Cancelled Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $booking = $this->loadBookingData($this->hotelBookingId);
        $bookingData = $this->transformBookingData($booking);

        $payment = $this->loadPaymentData($booking->ActivePayment()->id);
        $paymentData = $this->transformPaymentData($payment);

        //
        return new Content(
            view: 'emails.hotel-booking-cancelled',
            with: [
                'booking_data' => $bookingData ,
                'payment_data' => $paymentData
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
