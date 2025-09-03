<?php

namespace App\Jobs;

use App\Mail\FlightBookingPendingEmail;
use App\Models\FlightBooking;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlightBookingPendingEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3 ;
    public int $timeout = 30 ; // seconds
    public int $backoff = 10 ; // seconds between retries

    /**
     * Create a new job instance.
     */
    public $bookingId;
    public function __construct($bookingId  )
    {
        $this->bookingId = $bookingId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $booking = FlightBooking::with([
            'user',
            'flightCabin.flight.departureAirport.city',
            'flightCabin.flight.departureAirport.country',
            'flightCabin.flight.arrivalAirport.city',
            'flightCabin.flight.arrivalAirport.country',
        ])->findOrFail($this->bookingId);
        $flight = $booking->flightCabin->flight ;

        $data = [
            'user_name'=>$booking->user->name ,
            'flightCabin'=>$booking->flightCabin->class_name ,
            'flight_number' => $flight->flight_number,
            'departure' => $flight->departureAirport->city->name . ', ' . $flight->departureAirport->country->name,
            'arrival' => $flight->arrivalAirport->city->name . ', ' . $flight->arrivalAirport->country->name,
            'booking_date' => Carbon::parse($booking->booking_date)->format('Y-m-d H:i:s'),
            'seat_number'=>$booking->seat_number ,
            'status'=>$booking->status ,
        ];
        Mail::to($booking->user->email)->send(new FlightBookingPendingEmail($data));
    }
}
