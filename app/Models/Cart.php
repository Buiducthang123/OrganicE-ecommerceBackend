<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Cart extends Model
{
    use HasFactory;
   
    function user() {
        return $this->hasOne(User::class);
    }

    function cartItems() {
        return $this->hasMany(CartItem::class);
    }
    function products() {
        return $this->belongsToMany(Product::class,'cart_items','cart_id','product_id');
    }

}
