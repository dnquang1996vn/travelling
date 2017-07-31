<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function trip()
    {
        return $this->belongsTo('App\Trip');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function parent()
    {
        return $this->hasOne('App\Comment','id','parent_id');
    }
    public function children()
    {
        return $this->hasMany('App\Comment','parent_id','id');
    }
}
