<?php

namespace App\Policies;

use App\Models\FlightCabin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FlightCabinPolicy
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
    public function view(User $user, FlightCabin $flightCabin): bool
    {
        return $user->hasAnyRole(['admin','flight_agent']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin','flight_agent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FlightCabin $flightCabin): bool
    {
        return $user->hasAnyRole(['admin','flight_agent']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FlightCabin $flightCabin): bool
    {
        return $user->hasAnyRole(['admin','flight_agent']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FlightCabin $flightCabin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FlightCabin $flightCabin): bool
    {
        return false;
    }
}
