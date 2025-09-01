<?php

namespace App\Console\Commands;

use App\Models\HotelBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirePendingHotelBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-pending-hotel-bookings';

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

        $threshold = $now->copy()->addDay();

        $bookings = HotelBooking::where('status', 'pending')
            ->where('check_in_date', '<=', $threshold)
            ->get();

        foreach ($bookings as $booking) {
            $booking->status = 'cancelled';
            $booking->save();
        }

        $this->info("Processed {$bookings->count()} pending hotel bookings.");
    }
}
