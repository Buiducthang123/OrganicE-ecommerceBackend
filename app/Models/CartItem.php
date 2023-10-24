<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ['quantity'];
    
    protected $table = 'cart_items';
    function cart() {
        return $this->belongsTo(Cart::class);
    }
    

}
