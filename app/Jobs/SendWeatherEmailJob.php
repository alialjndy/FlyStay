<?php

namespace App\Jobs;

use App\Mail\WeatherEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendWeatherEmailJob implements ShouldQueue
{
    use Queueable , SerializesModels , Dispatchable , InteractsWithQueue;
    public int $tries = 3 ;
    public int $timeout = 30 ;
    public int $backoff = 10 ;
    /**
     * Create a new job instance.
     */
    public $userId ;
    public $city ;
    public $targetDate ;
    public function __construct($userId , $city , $targetDate)
    {
        $this->userId = $userId ;
        $this->city = $city ;
        $this->targetDate = $targetDate ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $user = User::findOrFail($this->userId);
            Mail::to($user->email)->send(
                new WeatherEmail($this->city , $this->targetDate)
            );
        }catch(Throwable $e){
            Log::info('حدث خطأ أثناء جلب بيانات الطقس في التنفيذ عن طريق الجوب');
        }
    }
}
