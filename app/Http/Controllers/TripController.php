<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;

class TripController extends Controller
{
    public function show($id)
    {
    	$trip = Trip::find($id);
    	if ($trip == null)
    	{
    		return "this trip does not exist";
    	}
    	else
    	{
    		return view('trip',[
    		'trip' => $trip,
    		]);
    	}
    	
    }
}
