<?php

namespace App\Policies;

use App\User;
use App\Trip;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TripPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the trip.
     *
     * @param  \App\User  $user
     * @param  \App\Trip  $trip
     * @return mixed
     */
    public function view(User $user, Trip $trip)
    {
        //
    }

    /**
     * Determine whether the user can create trips.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the trip.
     *
     * @param  \App\User  $user
     * @param  \App\Trip  $trip
     * @return mixed
     */
    public function update(User $user, Trip $trip)
    {
          return $user->id === $trip->owner_id;
    }

    public function join(User $user, Trip $trip)
    {
        if ($this->update($user,$trip))
        {
            return false;
        }
        else
        {
            return true;
        }

    }
    /**
     * Determine whether the user can delete the trip.
     *
     * @param  \App\User  $user
     * @param  \App\Trip  $trip
     * @return mixed
     */
    public function delete(User $user, Trip $trip)
    {
        //
    }
}
