<?php

namespace App\Policies;

use App\Models\HotelBooking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HotelBookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin','hotel_agent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HotelBooking $hotelBooking)
    {
        return $user->hasAnyRole(['admin','hotel_agent']) || $user->id === $hotelBooking->user_id;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['customer','hotel_agent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HotelBooking $hotelBooking): bool
    {
        return $user->hasAnyRole(['hotel_agent']) || ($user->hasRole('customer') && $user->id === $hotelBooking->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HotelBooking $hotelBooking): bool
    {
        return $user->hasRole('hotel_agent') ;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HotelBooking $hotelBooking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HotelBooking $hotelBooking): bool
    {
        return false;
    }
}
