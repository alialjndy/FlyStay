<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendAuthenticatedEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected User $user ;
    public function __construct(User $user)
    {
        $this->user = $user ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $user = User::findOrFail($this->user);
        if ($this->user && $this->user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$this->user->hasVerifiedEmail()) {
            $this->user->sendEmailVerificationNotification();
            Log::info("تم إرسال رابط التحقق إلى {$this->user->email}");
        } else {
            Log::warning("لم يتم إرسال رابط التحقق. المستخدم غير موجود أو تم التحقق منه مسبقًا.");
        }
    }
}
