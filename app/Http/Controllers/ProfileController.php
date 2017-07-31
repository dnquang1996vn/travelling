<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    public function show ($id)
    {   
        $user = User::find($id);
        if ($user == null)
        {
            return ("dm oc cho");
        }
        else
        {
            $planning_creates = $user->created_trips;
            return view('profile',[
                'user' => $user,
                'planning_creates' => $planning_creates,
            ]);
        }
    }
}
