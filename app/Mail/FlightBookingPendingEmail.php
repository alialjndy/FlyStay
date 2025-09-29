<?php

namespace App\Mail;

use App\Models\FlightBooking;
use App\Traits\LoadsFlightBookingData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address ;
use Illuminate\Support\Facades\Log;

class FlightBookingPendingEmail extends Mailable
{
    use Queueable, SerializesModels , LoadsFlightBookingData;

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
                new Address('support@FlyStay.com', 'support')
            ],
            subject: 'Flight Booking Pending Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $booking = $this->loadFlightBookingData($this->bookingId);
        $bookingData = $this->transformBookingData($booking);
        return new Content(
            view: 'emails.flight-booking-pending',
            with:[
                'booking_data'=>$bookingData
            ],
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
