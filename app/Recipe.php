<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $table = "recipe";

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function recipeImages()
    {
        return $this->hasMany(\App\RecipeImage::class);
    }

    public function getIsMainUserAttribute()
    {
        return $this->attributes['user_id'] == 1;
    }

    public function getMainImageAttribute()
    {
        return $this->recipeImages()->where(['recipe_id' => $this->attributes['id']])->orderBy('created_at')->first();
    }
}
