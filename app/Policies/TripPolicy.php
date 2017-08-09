<?php

namespace App\Policies;

use App\User;
use App\Trip;
use App\Joined_trip;
use App\Followed_trip;
use App\Joined_request;
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

    public function normal(User $user,Trip $trip)
    {
        if ($this->update($user,$trip)) {
            return false;
        }
        else {
            return (($trip->status) == 0);
        }
    }

    public function join(User $user, Trip $trip)
    {
        $joined_trips = Joined_trip::where('trip_id', $trip->id)
                            ->where('user_id', $user->id)->get();
        return (($joined_trips->count() == 0)
                &&($this->normal($user,$trip)));
                    
    }

    public function follow(User $user, Trip $trip)
    {
        $followed_trips = Followed_trip::where('trip_id', $trip->id)
                        ->where('user_id', $user->id)->get();
        return (($followed_trips->count() == 0)
            &&($this->normal($user,$trip)));
        
    }

    public function unfollow(User $user, Trip $trip)
    {
        $followed_trips = Followed_trip::where('trip_id', $trip->id)
                        ->where('user_id', $user->id)->get();
        return (($followed_trips->count() > 0)
            &&($this->normal($user,$trip)));
        
    }

    public function outTrip (User $user, Trip $trip)
    {
        $joined_trips = Joined_trip::where('trip_id', $trip->id)
                            ->where('user_id', $user->id)->get();
        return (($joined_trips->count() > 0)
            &&($this->normal($user,$trip)));
       
    }

    public function joinRequest (User $user, Trip $trip)
    {
        $joined_trips = Joined_trip::where('trip_id', $trip->id)
                            ->where('user_id', $user->id)->get();
        $joined_requests = Joined_request::where('trip_id', $trip->id)
                            ->where('user_id', $user->id)->get();
        return (($joined_trips->count() == 0)
            && ($joined_requests->count() == 0)
            &&($this->normal($user,$trip)));
    }

    public function cancelRequest (User $user, Trip $trip)
    {
        $joined_trips = Joined_trip::where('trip_id', $trip->id)
                            ->where('user_id', $user->id)->get();
        $joined_requests = Joined_request::where('trip_id', $trip->id)
                            ->where('user_id', $user->id)->get();
        return (($joined_trips->count() == 0)
            && ($joined_requests->count() > 0)
            &&($this->normal($user,$trip)));
    }

    public function startTrip (User $user, Trip $trip)
    {
        if ($this->update($user,$trip))
        {
           return ($trip->status == 0);
        }
        else return false;
    }

    public function finishTrip (User $user, Trip $trip)
    {
        if ($this->update($user,$trip))
        {
           return ($trip->status == 1);
        }
        else return false;
    }

    public function cancelTrip (User $user, Trip $trip)
    {
        if ($this->update($user,$trip))
        {
           return ($trip->status == 0);
        }
        else return false;
    }
   
}
