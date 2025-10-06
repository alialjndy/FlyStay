<?php

namespace App\Mail;

use App\Traits\Weather;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class WeatherEmail extends Mailable
{
    use Queueable, SerializesModels , Weather;

    /**
     * Create a new message instance.
     */
    protected $city ;
    protected $targetDate ;
    public function __construct($city , $targetDate)
    {
        $this->city = $city ;
        $this->targetDate = $targetDate ;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@FlyStay.com','no-reply'),
            replyTo: [
                new Address('support@FlyStay.com','support')
            ],
            subject: 'Weather Forecast Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $weather_data = $this->get_weather($this->city , $this->targetDate);
        return new Content(
            view: 'emails.weather',
            with: [
                'weather_data' => $weather_data
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
