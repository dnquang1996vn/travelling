<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTrip;
use Intervention\Image\Facades\Image;
use App\Trip;
use Auth;
use App\Plan;

class CreateTripController extends Controller
{
    //
    function create() 
    {
        return view('map');
    }
    function store(Request $request)
    {      
        $new_trip = json_decode($request->new_trip,true);
        $plans = json_decode($request->plans,true);
        $trip_cover = $request->trip_cover;
        $trip = array('new_trip' => $new_trip,'trip_cover' => $trip_cover);

        //passing array to validate
        Validator::make($trip,[
            'new_trip.name'      => 'required',
            'new_trip.time_start'=> 'required|date|after:'.date('Y-m-d H:i:s'),
            'new_trip.time_end'  => 'required|date|after:new_trip.time_start',
            'trip_cover'         =>'required|image|mimes:jpeg,jpg,png,gif',
        ])->validate();
        foreach ($plans as $i => $plan) {
            //if has 1 plan
            if( $i == 0 ){
                Validator::make($plan,[
                    'from'   => 'required',
                    'to'   => 'required',
                    'time_start' => 'required|date|before:time_end|after:'.date('Y-m-d H:i:s'),
                    'time_end' => 'required|date|after:time_start',
                    'vehicle'    =>'required',
                    'activity'   =>'required',
                ])->validate();
            }
            // more than 1 plan
            else {
                Validator::make($plans,[
                    $i.'.from'   => 'required|same:'.($i-1).'.to',
                    $i.'.src_lat'   => 'required|same:'.($i-1).'.dest_lat',
                    $i.'.src_lng'   => 'required|same:'.($i-1).'.dest_lng',
                    $i.'.to'   => 'required',
                    $i.'.time_start' => 'required|date|before:'.$i.'.time_end'.'|after:'.($i - 1).'.time_end',
                    $i.'.time_end' => 'required|date|after:'.$i.'.time_start',
                ])->validate();
            }
        }
        // If form has a file(image) or not ?
        if($request->hasFile('trip_cover')) {
            // retrieve all of input data
            $file = $request->file('trip_cover');
            //get Image Name
            $name = time().$file->getClientOriginalName();
            //Store the file at our public/images
            $file->move(public_path().'/image/cover/',$name);
            //get path
            $imagePath = public_path().'/image/cover/'.$name;
            //resize
            $image = Image::make($imagePath)->resize(1150,300);
            //save
            $image->save($imagePath);
            echo "insert image";
        }

        //insert to Trip Table using ORM
        $newTrip                    = new Trip;
        $newTrip -> owner_id        = Auth::user()->id;
        $newTrip -> name            = json_decode($request->new_trip)->name;
        $newTrip -> starting_time   = json_decode($request->new_trip)->time_start;
        $newTrip -> ending_time     = json_decode($request->new_trip)->time_end;
        $newTrip -> description     = json_decode($request->new_trip)->description;
        $newTrip -> cover           = "image/cover/".$name;
        $newTrip -> status          = 0;
        $newTrip -> save();
        echo('insert trip successfull');

        //insert to Plan Table using ORM
        foreach ($plans as $i => $plan) {
            $newPlan = new Plan;
            $newPlan -> trip_id = $newTrip -> id;
            $newPlan -> src_lat = $plan['src_lat'];
            $newPlan -> src_lng = $plan['src_lng'];
            $newPlan -> src_name = $plan['from'];
            $newPlan -> dest_lat = $plan['dest_lat'];
            $newPlan -> dest_lng = $plan['dest_lng'];
            $newPlan -> dest_name = $plan['to'];
            $newPlan -> starting_time = $plan['time_start'];
            $newPlan -> ending_time = $plan['time_end'];
            $newPlan -> vehicle = $plan['vehicle'];
            $newPlan -> activity = $plan['activity'];
            $newPlan -> number = ($i + 1);
            $newPlan -> save();
        }
        
    }
}
