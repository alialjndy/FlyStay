<?php

namespace App\Jobs;

use App\Mail\FlightBookingConfirmedEmail;
use App\Models\FlightBooking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class FlightBookingConfirmedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3 ;
    public int $timeout = 30 ; // seconds
    public int $backoff = 10 ; // seconds between retries

    /**
     * Create a new job instance.
     */
    protected  $paymentId ;
    protected  $bookingId ;
    public function __construct($paymentId , $bookingId)
    {
        $this->paymentId = $paymentId ;
        $this->bookingId = $bookingId ;
    }

    /**
     * Execute the job.
     * This method will be called by the queue worker.
     * It retrieves the booking and payment details,
     * prepares the email data, and sends the email
     * to the user who made the booking.
     */
    public function handle(): void
    {
        // Retrieve booking with important relations (user, flight details, airports)
        $booking = FlightBooking::with([
            'user',
            'flightCabin.flight.departureAirport.city',
            'flightCabin.flight.departureAirport.country',
            'flightCabin.flight.arrivalAirport.city',
            'flightCabin.flight.arrivalAirport.country',
        ])->findOrFail($this->bookingId);

        $payment = Payment::findOrFail($this->paymentId);

        $flight = $booking->flightCabin->flight ;

        $data = [
            'user_name'     => $booking->user->name ,
            'flightCabin'   => $booking->flightCabin->class_name ,
            'flight_number' => $flight->flight_number,
            'departure'     => $flight->departureAirport->city->name . ', ' . $flight->departureAirport->country->name,
            'arrival'       => $flight->arrivalAirport->city->name . ', ' . $flight->arrivalAirport->country->name,
            'booking_date'  => $booking->booking_date,
            'seat_number'   =>$booking->seat_number ,
            'status'        =>$booking->status ,

            // payment info

            'payment_amount' => $payment->amount,
            'payment_date'   => Carbon::parse($payment->date)->format('Y-m-d H:i:s'),
            'payment_method' => $payment->method,
            'payment_status' => $payment->status,
        ];

        // Send the confirmation email to the user
        Mail::to($booking->user->email)->send(new FlightBookingConfirmedEmail($data)) ;
    }
}
