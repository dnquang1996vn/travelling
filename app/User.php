<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password','gender','work','phone','birthday','about','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
        return $this->hasMany('App\Joined_trip');
    }

    public function joined_requests()
    {
        return $this->hasMany('App\Joined_request');
    }

    public function created_trips()
    {
        return $this->hasMany('App\Trip','owner_id');
    }
}
