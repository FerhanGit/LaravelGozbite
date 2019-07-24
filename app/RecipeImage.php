<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeImage extends Model
{
    public $table = "recipe_images";

    public function recipe()
    {
        $this->belongsTo(\App\Recipe::class);
    }
}
