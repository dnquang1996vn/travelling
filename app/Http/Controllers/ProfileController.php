<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Trip;
use App\Joined_trip;
use App\User;
use App\Followed_trip;
use App\Joined_request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfile;
use App\Http\Requests\UploadAvatar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show ($id)
    {   
        $user = User::find($id);
        if ($user == null) {
            return view('notfound');
        }
        else {
            $created_trips = $user->created_trips;
            $followed_trips = Followed_trip::with(['user','trip'])->where('user_id',$user->id)->get();
            $joined_trips = Joined_trip::with(['user','trip'])->where('user_id',$user->id)->get();
            return view('profile',[
                'user' => $user,
                'created_trips'  => $created_trips,
                'followed_trips' => $followed_trips,
                'joined_trips'   => $joined_trips,
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

    public function upload(UploadAvatar $request,$id)
    {   
        //dd($request->all());
        if (Auth::user()->id == $id){
            
            //$path = $request->avatarInput->store('image/avatar');
            $path = Storage::putFile(
                'avatars', $request->avatarInput);
            $user = User::find($id);
            Storage::delete($user->avatar);           
            $user->avatar = $path;
            $user->save();
            return $user;
        }
    }
}
