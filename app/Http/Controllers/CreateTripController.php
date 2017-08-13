<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTrip;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Trip;
use Auth;
use App\Plan;

class CreateTripController extends Controller
{
    //
    function create() 
    {
        return view('create_trip');
    }
    function store(Request $request)
    {      
        $new_trip = json_decode($request->new_trip,true);
        $plans = json_decode($request->plans,true);
        $trip_cover = $request->trip_cover;
        $trip = array('new_trip' => $new_trip,'trip_cover' => $trip_cover,'plans' => $plans);

        //passing array to validate
        Validator::make($trip,[
            'new_trip.name'      => 'required',
            'new_trip.time_start'=> 'required|date|after:'.date('Y-m-d H:i:s'),
            'new_trip.time_end'  => 'required|date|after:new_trip.time_start',
            'trip_cover'         =>'required|image|mimes:jpeg,jpg,png,gif',
            'plans'               => 'required|array|min:2',
        ])->validate();
        foreach ($plans as $i => $plan) {
            Validator::make($plans,[
                '*.from'       => 'required',
                '*.to'         => 'required',
                '*.time_start' => 'required|date|after:'.date('Y-m-d H:i:s'),
                '*.time_end'   => 'required|date|after:time_start',
                '*.vehicle'    =>'required',
                '*.activity'   =>'required',
            ])->validate();
            //if has 1 plan
            if( $i > 0 ){
                //if not last plans
                if($i != (sizeof($plans)-1)) {
                    Validator::make($plans,[
                        $i.'.from'       => 'same:'.($i-1).'.to',
                        $i.'.src_lat'    => 'same:'.($i-1).'.dest_lat',
                        $i.'.src_lng'    => 'same:'.($i-1).'.dest_lng',
                        $i.'.time_start' => 'required|date|before:'.$i.'.time_end'.'|after:'.($i - 1).'.time_end',
                        $i.'.time_end'   => 'required|date|after:'.$i.'.time_start',
                    ])->validate();                    
                }else {
                    Validator::make($plans,[
                        $i.'.from'       => 'same:'.($i-1).'.to',
                        $i.'.to'       => 'same:0.from',
                        $i.'.src_lat'    => 'same:'.($i-1).'.dest_lat',
                        $i.'.src_lng'    => 'same:'.($i-1).'.dest_lng',
                        $i.'.time_start' => 'required|date|before:'.$i.'.time_end'.'|after:'.($i - 1).'.time_end',
                        $i.'.time_end'   => 'required|date|after:'.$i.'.time_start',
                    ])->validate(); 
                }
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
            $newPlan                    = new Plan;
            $newPlan -> trip_id         = $newTrip -> id;
            $newPlan -> src_lat         = $plan['src_lat'];
            $newPlan -> src_lng         = $plan['src_lng'];
            $newPlan -> src_name        = $plan['from'];
            $newPlan -> dest_lat        = $plan['dest_lat'];
            $newPlan -> dest_lng        = $plan['dest_lng'];
            $newPlan -> dest_name       = $plan['to'];
            $newPlan -> starting_time   = $plan['time_start'];
            $newPlan -> ending_time     = $plan['time_end'];
            $newPlan -> vehicle         = $plan['vehicle'];
            $newPlan -> activity        = $plan['activity'];
            $newPlan -> save();
        }
        
    }

    function editForm($id) {
        $trip = Trip::find($id);
        if(Auth::user()->id != $trip->owner_id && $trip->status != 0){
                  return redirect()->route('home');
        }
        $plans = $trip->plans;
        return view('edit_trip')->with('trip',$trip)->with('plans',$plans);
    }

    function editTrip(Request $request,$id) {
        $new_trip = json_decode($request->new_trip,true);
        $plans = json_decode($request->plans,true);
        if($request->trip_cover != null){
        //if change trip cover validate image
            $trip_cover = $request->trip_cover;
            $trip = array('new_trip' => $new_trip,'trip_cover' => $trip_cover, 'plans' => $plans);
            Validator::make($trip,[
                'new_trip.name'       => 'required',
                'new_trip.time_start' => 'required|date|after:'.date('Y-m-d H:i:s'),
                'new_trip.time_end'   => 'required|date|after:new_trip.time_start',
                'trip_cover'          => 'required|image|mimes:jpeg,jpg,png,gif',
                'plans'               => 'required|array|min:2',
            ])->validate();            
        }else{
            $trip = array('new_trip' => $new_trip, 'plans' => $plans);
            Validator::make($trip,[
                'new_trip.name'       => 'required',
                'new_trip.time_start' => 'required|date|after:'.date('Y-m-d H:i:s'),
                'new_trip.time_end'   => 'required|date|after:new_trip.time_start',
                'plans'               => 'required|array|min:2',
            ])->validate();           
        }

        foreach ($plans as $i => $plan) {
            Validator::make($plans,[
                '*.from'       => 'required',
                '*.to'         => 'required',
                '*.time_start' => 'required|date|after:'.date('Y-m-d H:i:s'),
                '*.time_end'   => 'required|date|after:time_start',
                '*.vehicle'    =>'required',
                '*.activity'   =>'required',
            ])->validate();
            //if has 1 plan
            if( $i > 0 ){
                //if not last plans
                if($i != (sizeof($plans)-1)) {
                    Validator::make($plans,[
                        $i.'.from'       => 'same:'.($i-1).'.to',
                        $i.'.src_lat'    => 'same:'.($i-1).'.dest_lat',
                        $i.'.src_lng'    => 'same:'.($i-1).'.dest_lng',
                        $i.'.time_start' => 'required|date|before:'.$i.'.time_end'.'|after:'.($i - 1).'.time_end',
                        $i.'.time_end'   => 'required|date|after:'.$i.'.time_start',
                    ])->validate();                    
                }else {
                    Validator::make($plans,[
                        $i.'.from'       => 'same:'.($i-1).'.to',
                        $i.'.to'       => 'same:0.from',
                        $i.'.src_lat'    => 'same:'.($i-1).'.dest_lat',
                        $i.'.src_lng'    => 'same:'.($i-1).'.dest_lng',
                        $i.'.time_start' => 'required|date|before:'.$i.'.time_end'.'|after:'.($i - 1).'.time_end',
                        $i.'.time_end'   => 'required|date|after:'.$i.'.time_start',
                    ])->validate(); 
                }
            }
        }//end foreach

        $current_trip  = Trip::find($id);
        if(Auth::user()->id != $current_trip->owner_id){
            return redirect()->route('home');
        }else {     
            // If form has a file(image) or not ?
            if($request->hasFile('trip_cover')) {
                $file = $request->file('trip_cover');
                $name = time().$file->getClientOriginalName();
                $file->move(public_path().'/image/cover/',$name);
                $imagePath = public_path().'/image/cover/'.$name;
                $image = Image::make($imagePath)->resize(1150,300);
                $image->save($imagePath);
                //remove old image
                Storage::delete($current_trip->cover);
                //update url image 
                $current_trip -> cover       = "image/cover/".$name;
            }
            $current_trip -> name            = json_decode($request->new_trip)->name;
            $current_trip -> starting_time   = json_decode($request->new_trip)->time_start;
            $current_trip -> ending_time     = json_decode($request->new_trip)->time_end;
            $current_trip -> description     = json_decode($request->new_trip)->description;   
            $current_trip -> save();

            //delete all old plan of trip
            foreach($current_trip->plans as $plan) {
                $plan -> delete();
            }
            //insert new Plan to Plan Table
            foreach ($plans as $i => $plan) {
                $newPlan                    = new Plan;
                $newPlan -> trip_id         = $current_trip -> id;
                $newPlan -> src_lat         = $plan['src_lat'];
                $newPlan -> src_lng         = $plan['src_lng'];
                $newPlan -> src_name        = $plan['from'];
                $newPlan -> dest_lat        = $plan['dest_lat'];
                $newPlan -> dest_lng        = $plan['dest_lng'];
                $newPlan -> dest_name       = $plan['to'];
                $newPlan -> starting_time   = $plan['time_start'];
                $newPlan -> ending_time     = $plan['time_end'];
                $newPlan -> vehicle         = $plan['vehicle'];
                $newPlan -> activity        = $plan['activity'];
                $newPlan -> save();
            }
        }
    }
}
