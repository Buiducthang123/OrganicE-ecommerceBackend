<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $appends = ['CmtNumber'];
    protected $fillable = ["category_id","title","image","content"];
    function category()
    {
        return $this->belongsTo(category::class);
    }

    public function comments()
    {
        return $this->belongsToMany(User::class, "comments");
    }
    function getCmtNumberAttribute() {
        return $this->comments()->count();
    }
}
