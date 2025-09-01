<?php

namespace App\Console\Commands;

use App\Models\HotelBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireConfirmedHotelBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-confirmed-hotel-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark confirmed hotel bookings as complete if their check-out date has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $confirmedHotelBookings = HotelBooking::where('status','confirmed')
            ->where('check_out_date','<=',$now)->get();

        foreach($confirmedHotelBookings as $booking){
            $booking->status = 'complete';
            $booking->update(['status'=>'complete']);
        }
        $this->info("Processed {$confirmedHotelBookings->count()} confirmed bookings.");
    }
}
