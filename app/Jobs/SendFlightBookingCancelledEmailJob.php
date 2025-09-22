<?php

namespace App\Jobs;

use App\Mail\FlightBookingCancelledEmail;
use App\Models\FlightBooking;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFlightBookingCancelledEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3 ;
    public int $timeout = 30 ; // seconds
    public int $backoff = 10 ; // seconds between retries

    /**
     * Create a new job instance.
     */
    protected $userId ;
    protected $bookingId ;
    public function __construct($userId , $bookingId)
    {
        $this->userId = $userId ;
        $this->bookingId = $bookingId ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->userId);
        // Retrieve booking with important relations (user, flight details, airports)
        $booking = FlightBooking::with([
            'user',
            'flightCabin.flight.departureAirport.city',
            'flightCabin.flight.departureAirport.country',
            'flightCabin.flight.arrivalAirport.city',
            'flightCabin.flight.arrivalAirport.country',
        ])->findOrFail($this->bookingId);

        $flight = $booking->flightCabin->flight ;
        $data = [
            'user'          =>$booking->user->name ,
            'flightCabin'   =>$booking->flightCabin->class_name ,
            'flight_number' =>$booking->flightCabin->flight->flight_number ,
            'departure'     =>$flight->departureAirport->city->name . ', ' . $flight->departureAirport->country->name,
            'arrival'       =>$flight->arrivalAirport->city->name . ', ' . $flight->arrivalAirport->country->name,
        ];
        Mail::to($user->email)->send(new FlightBookingCancelledEmail($data));
    }
}
