<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use App\Joined_trip;
use App\User;
use App\Followed_trip;
use App\Joined_request;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function show($id)
    {
    	$trip = Trip::find($id);
    	$user = Auth::user();
    	if ($trip == null)
    	{
    		return view('notfound');
    	}
    	else
    	{  
            $comments = Comment::with(['images', 'user', 'children'])
                            ->where('parent_id', null)
                            ->where('trip_id', $trip->id)
                            ->orderBy('id','desc')->take(10)->get();
            $joined_trips = Joined_trip::with('user')->where('trip_id', $id)->get();
            $joined_requests = $trip->joined_requests;
            if ($user == null){
                return view('trip',[
                    'user'         => $user,
                    'trip'         => $trip,
                    'joined_trips' => $joined_trips,
                ]);
            } else {
               if ($user->can('update',$trip)){
                return view('ownerTrip',[
                    'user'            => $user,
                    'trip'            => $trip,
                    'joined_trips'    => $joined_trips,
                    'joined_requests' => $joined_requests,
                    'comments'        => $comments,
                ]);
            }
                else {
                    return view('normalTrip',[
                    'user' => $user,
                    'trip' => $trip,
                    'joined_trips' => $joined_trips,
                    ]);
                } 
            }
            
    	}
    }

    public function follow(Request $request)
    {   
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('follow',$trip)){
            $followed_trip = new Followed_trip;
            $followed_trip->user_id = $request->user_id;
            $followed_trip->trip_id = $request->trip_id;
            $followed_trip->save();
            return 1;
        }
    }

    public function unfollow(Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('unfollow',$trip)){
            $followed_trip = Followed_trip:: where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
        }
    }

    public function joinTrip(Request $request)
    {   
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('joinRequest',$trip)){
            $joined_request = new Joined_request;
            $joined_request->user_id = $request->user_id;
            $joined_request->trip_id = $request->trip_id;
            $joined_request->message = $request->message;
            $joined_request->save();
            return $joined_request;
        }
    }

    public function cancelRequest (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('cancelRequest',$trip)){
            $joined_request = Joined_request::where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
            return "canceled";
        }
    }

    public function acceptRequest (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $owner = Auth::user();
        if ($owner->can('update',$trip)){
            $joined_requests = Joined_request::where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
            $joined_trip = new Joined_trip;
            $joined_trip->user_id = $request->user_id;
            $joined_trip->trip_id = $request->trip_id;
            $joined_trip->save();
            return $joined_trip;
        }
    }

    public function denyRequest (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $owner = Auth::user();
        if ($owner->can('update',$trip)){
            $joined_requests = Joined_request::where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
        }
    }

    public function outTrip (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        $joined_trip = Joined_trip::where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id);
        if ($user->can('outTrip',$trip)){
            $joined_trip = Joined_trip::where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
        }
    }

    public function kick (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $owner = Auth::user();
        if ($owner->can('update',$trip)){
            $joined_trip = Joined_trip::where ('trip_id', $request->trip_id)
                                ->where('user_id', $request->user_id)->delete();
        }
    }

    public function startTrip (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('startTrip',$trip)){
            $trip->status = 1;
            $trip->save();
            return $trip;
        }
        //else return 1;
    }

    public function finishTrip (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('finishTrip',$trip)){
            $trip->status =2;
            $trip->save();
        }
    }

    public function cancelTrip (Request $request)
    {
        $trip = Trip::find($request->trip_id);
        $user = Auth::user();
        if ($user->can('cancelTrip',$trip)){
            $trip->status = 3;
            $trip->save();
        }
        return 3;
    }
}
