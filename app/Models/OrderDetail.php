<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // protected $fillable = ["products_order","total_price","address_shipping","payment_method","note"] ;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDecodedProductsOrderAttribute()
    {
        return json_decode($this->attributes['products_order'], true);
    }
    
}