<?php

namespace App\Console\Commands;

use App\Models\FlightBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirePendingFlightBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-pending-flight-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $threshold = $now->copy()->addHours(2);

        $bookings = FlightBooking::where('status', 'pending')
            ->whereHas('flightCabin.flight', function($q) use ($threshold){
                $q->where('departure_time', '<=', $threshold);
            })
            ->get();

        foreach ($bookings as $booking) {
            $booking->status = 'cancelled';
            $booking->save();
            $booking->flightCabin->increment('available_seats');
        }

        $this->info("Processed {$bookings->count()} pending bookings.");
    }
}
