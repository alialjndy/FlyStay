<?php

namespace App\Mail;

use App\Traits\LoadsHotelBookingData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class HotelBookingPendingEmail extends Mailable
{
    use Queueable, SerializesModels , LoadsHotelBookingData;

    /**
     * Create a new message instance.
     */
    public $bookingId ;

    public function __construct($bookingId)
    {
        $this->bookingId = $bookingId ;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@FlyStay.com','FlyStay'),
            replyTo: [
                new Address('Support@FlyStay.com','Support')
            ] ,
            subject: 'Hotel Booking Pending Email',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $booking = $this->loadBookingData($this->bookingId);
        $transformData = $this->transformBookingData($booking);
        return new Content(
            view: 'emails.hotel-booking-pending',
            with:[
                'booking_data' => $transformData
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
