<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCity extends Model
{
  use HasFactory;
    /**
     * Fields that are mass assignable
     *
     * @var array
     */
  protected $fillable = ['name','type','shipping_province_id'];

  public function shipping_province(){
      return $this->belongsTo('App\Models\ShippingProvince');
  }

  public function shipping_subdistricts(){
      return $this->hasMany('App\Models\ShippingSubdistrict');
  }

}
