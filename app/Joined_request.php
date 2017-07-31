<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Joined_request extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function trip()
    {
        return $this->belongsTo('App\Trip');
    }
}
