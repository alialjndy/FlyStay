<?php

namespace App\Policies;

use App\Models\FlightBooking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FlightBookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin','flight_agent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FlightBooking $flightBooking): bool
    {
        return ($user->hasAnyRole(['admin','flight_agent']) || ($user->hasRole('customer') && $user->id == $flightBooking->user_id));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['customer','flight_agent','admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FlightBooking $flightBooking): bool
    {
        return ($user->hasAnyRole(['flight_agent','admin']) || $flightBooking->user_id == $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FlightBooking $flightBooking): bool
    {
        return $user->hasRole(['flight_agent','admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FlightBooking $flightBooking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FlightBooking $flightBooking): bool
    {
        return false;
    }
}
