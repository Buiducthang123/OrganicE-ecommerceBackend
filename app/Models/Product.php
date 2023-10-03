<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    function category() {
        return $this->belongsTo(category::class);
    }
    function thumbnails(){
        return $this->hasMany(Thumbnail::class);
    }
}
