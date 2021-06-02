<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingProvince extends Model
{
  use HasFactory;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
  protected $fillable = ['name'];

    public function shipping_cities(){
        return $this->hasMany('App\Models\ShippingCity');
    }
}
