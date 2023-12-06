<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $appends = ['current_price','stock'];
    protected $fillable = [
        'name',
        'category_id',
        'type',
        'imageUrl',
        'quantity',
        // 'average_rating',
        'discount',
        'weight',
        'description',
        'price',
        
    ];
    public function getCurrentPriceAttribute() {
        return $this->price * (1 - $this->discount / 100);
    }
    public function getStockAttribute() {
        if($this->quantity==0){
            return true;
        }
        return false;
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name); // Tạo slug dựa trên trường name
        });
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name); // Cập nhật slug khi cập nhật trường name
        });
    }
    public function getRouteKeyName()
    {
        return 'slug'; // Sử dụng trường slug thay cho khóa chính mặc định (id) trong các tìm kiếm theo khóa ngoại
    }
    function category() {
        return $this->belongsTo(category::class);
    }
    function thumbnails(){
        return $this->hasMany(Thumbnail::class);
    }

    function carts() {
        return $this->belongsToMany(Cart::class,'cart_items','cart_id','product_id')->withPivot('quantity');
    }

    function usersWishList() {
        return $this->belongsToMany(User::class,'wish_lists');
    }

}
