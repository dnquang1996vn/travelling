<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use App\Joined_trip;
use App\User;

class TripController extends Controller
{
    public function show($id)
    {
    	$trip = Trip::find($id);
    	$joined_trips = Joined_trip::where('trip_id',$id)->get();
    	if ($trip == null)
    	{
    		return view('notfound');
    	}
    	else
    	{
    		return view('trip',[
    		'trip' => $trip,
    		'joined_trips' => $joined_trips,
    		]);
    	}
    	
    }
}
