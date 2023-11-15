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
    // public function getFormattedOrderAttribute()
    // {
    //     return [
    //         'id' => $this->id,
    //         'user_id' => $this->user_id,
    //         'products_order' => $this->decoded_products_order,
    //         'total_price' => $this->total_price,
    //         'address_shipping' => $this->address_shipping,
    //         'payment_method' => $this->payment_method,
    //         'note' => $this->note,
    //         'created_at' => $this->created_at,
    //         'updated_at' => $this->updated_at,
    //     ];
    // }
}