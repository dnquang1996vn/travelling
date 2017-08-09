<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $allTrips = Trip::orderBy('id','asc')->get();
        $newestTrips = Trip::orderBy('id','desc')->take(10)->get();
        $hottestTrips = Trip::orderBy('id','asc')->get(); //tam thoi the

        return view('home')->with('allTrips', $allTrips)
                            ->with('newestTrips', $newestTrips)
                            ->with('hottestTrips', $hottestTrips);
    }
}
