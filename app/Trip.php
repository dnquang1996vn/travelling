<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User','owner_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function followed_trips()
    {
        return $this->hasMany('App\Followed_trip');
    }

    public function joined_trips()
    {
        return $this->hasMany('App\Joined_trips');
    }

    public function joined_requests()
    {
        return $this->hasMany('App\Joined_request');
    }

    public function plans()
    {
        return $this->hasMany('App\Plan');
    }
}
