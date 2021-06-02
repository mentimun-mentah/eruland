<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shipping_subdistrict(){
        return $this->belongsTo('App\Models\ShippingSubdistrict');
    }

    public function products(){
        return $this->hasMany('App\Models\Product');
    }

    public function orders(){
        return $this->hasMany('App\Models\Order');
    }

    public function order_items(){
        return $this->hasMany('App\Models\OrderItem');
    }

    public function withdrawals(){
        return $this->hasMany('App\Models\Withdrawal');
    }

}
