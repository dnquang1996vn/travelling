<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use App\Joined_trip;
use App\User;
use App\Followed_trip;
use App\Joined_request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function show($id)
    {
    	$trip = Trip::find($id);
    	
    	if ($trip == null)
    	{
    		return view('notfound');
    	}
    	else
    	{  
            $joined_trips = Joined_trip::where('trip_id', $id)->get();
            $notFollowed = $this->checkNotFollowed($id);
            $isOwner = $this->checkOwner($id);
            $Joined = $this->checkJoined($id);
    		return view('trip',[
    		'trip' => $trip,
    		'joined_trips' => $joined_trips,
            'isOwner' => $isOwner,
            'notFollowed' => $notFollowed,
            'Joined' => $Joined,
    		]);
    	}
    }

    public function update(Request $request)
    {
        $user = Auth::user(); 
        if ($user->cannot('update', Post::class)) {
            dd('xxx');
        }
        dd('ddd');
    }
    public function follow(Request $request)
    {
        if ($this->checkNotFollowed($request->trip_id)){
            $followed_trip = new Followed_trip;
            $followed_trip->user_id = $request->user_id;
            $followed_trip->trip_id = $request->trip_id;
            $followed_trip->save();
            return 1;
        }
    }

    public function unfollow(Request $request)
    {
        if ($this->checkNotFollowed($request->trip_id)){
        
        }
        else {
            $followed_trip = Followed_trip:: where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
        }
    }

    public function checkOwner($id)
    {
        $trip = Trip::find($id);
        return (Auth::id() == $trip->owner_id);
    }

    public function checkNotFollowed($id)
    {   if ($this->checkOwner($id))
        {
            return false;
        }
        else
        {
            $followed_trips = Followed_trip::where('trip_id', $id)
                            ->where('user_id',Auth::id())->get();
            return ($followed_trips->count() == 0);
        }
        
    }
    
    public function checkJoined($id)
    {
        $joined_trips = Joined_trip::where('trip_id', $id)
                            ->where('user_id',Auth::id())->get();
        return ($joined_trips->count() > 0);
    }

    public function checkRequested($id)
    {
        if ($this->checkJoined($id)) {
            return false;
        }
        else {
            $joined_requests = Joined_request::where('trip_id', $id)
                            ->where('user_id',Auth::id())->get();
            return ($joined_requests->count() > 0); 
        }
    }

}
