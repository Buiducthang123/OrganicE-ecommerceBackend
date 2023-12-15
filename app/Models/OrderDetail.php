<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['approval_status'] ;
    protected $appends = ['count_products'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDecodedProductsOrderAttribute()
    {
        return json_decode($this->attributes['products_order'], true);
    }
    
    public function getProductsOrderAttribute() {
        return json_decode($this->attributes['products_order'], true);
    }
    function getCountProductsAttribute() {
        $products = json_decode($this->attributes['products_order'], true);
        return count($products);
    }
}