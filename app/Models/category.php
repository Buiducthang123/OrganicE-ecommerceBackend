<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class category extends Model
{
    use HasFactory;
    function products() {
        return $this->hasMany(Product::class);
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
   
}
