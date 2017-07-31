<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public function trip()
    {
        return $this->belongsTo('App\Trip');
    }
}
