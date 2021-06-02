<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = ['image','name','slug','desc','category','stock','price','weight','user_id'];

  public function user(){
      return $this->belongsTo('App\Models\User');
  }

  public function order_items(){
      return $this->hasMany('App\Models\OrderItem');
  }

}
