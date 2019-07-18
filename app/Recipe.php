<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $table = "recipe";


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getIsMainUserAttribute()
    {
        return $this->attributes['user_id'] == 1;
    }
}
