<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject , MustVerifyEmail , CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function sendPasswordResetNotification($token){
        $url = 'https://localhost:5173/reset-password?token=' . $token ;
        $this->notify(new ResetPasswordNotification($url));
    }
    public function scopeFilterRole($query , $roleName){
        return
        $query->when($roleName,function($query , $roleName){
            $query->whereHas('roles',function ($q) use ($roleName){
                $q->where('name',$roleName);
            });
        });
    }
    // كل الحجوزات
    public function flightBookings(){
        return $this->hasMany(FlightBooking::class,'user_id');
    }
    // الحجوزات الحالية
    public function upcommingFlightBookings(){
        return $this->flightBookings()->whereIn('status',['pending','confirmed'])->whereHas('flightCabin.flight',function($query){
            $query->where('departure_time','>',now());
        });
    }
    public function favorites(){
        return $this->hasMany(Favorite::class ,'user_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function activePayment($model , $bookingId){
        return $this->payments()
        ->where('status','pending')
        ->where('payable_type',$model)
        ->where('payable_id',$bookingId)
        ->first();
    }


}
