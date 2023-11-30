<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;
    protected $fillable = ['imageUrl','product_id'];
    function product() {
        return $this->belongsTo(Product::class);
    }
}
