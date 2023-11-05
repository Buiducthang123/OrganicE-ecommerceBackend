<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'avata'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
        'phone_number'
    ];


    protected $appends = ['permission'];

   
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function cart() {
        return $this->hasOne(Cart::class);
    }
   
    function productswishList() {
        return $this->belongsToMany(Product::class,'wish_lists');
    }
    function role(){
        return $this->belongsToMany(Role::class);
    }

    public function getPermissionAttribute()
    {
        return $this->role_id;
    }

    public function comments() {
        return $this->belongsToMany(Blog::class,"comments");
    }
}
