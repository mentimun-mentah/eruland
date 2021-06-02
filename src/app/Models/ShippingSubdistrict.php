<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSubdistrict extends Model
{
  use HasFactory;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
  protected $fillable = ['name','shipping_city_id'];

  public function shipping_city(){
      return $this->belongsTo('App\Models\ShippingCity');
  }

  public function users(){
      return $this->hasMany('App\Models\User');
  }


}
