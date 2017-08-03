<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfile;

class ProfileController extends Controller
{
    public function show ($id)
    {   
        $user = User::find($id);
        if ($user == null) {
            return view('notfound');
        }
        else {   
            $planning_creates = $user->created_trips;
            return view('profile',[
                'user' => $user,
                'planning_creates' => $planning_creates,
            ]);
        }
    }

    public function update(UpdateProfile $request,$id)
    {   
        if (Auth::user()->id == $id){
            $user = User::find($id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->work = $request->work;
            $user->gender = $request->gender;
            $user->birthday = $request->birthday;
            $user->about = $request->about;
            $user->save();
            return $user;
        };  
    }
}
