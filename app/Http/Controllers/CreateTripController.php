<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTrip;

class CreateTripController extends Controller
{
    //
    function create() 
    {
    	return view('map');
    }
    function store(Request $request)
    {
    	//code here
    	dd($request->all());
    }
}
