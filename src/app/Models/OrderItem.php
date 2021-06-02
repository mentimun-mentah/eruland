<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  use HasFactory;
  /**
   * Fields that are mass assignable
   *
   * @var array
  */
  protected $fillable = [
    'qty', 'price', 'code_shipping', 'service_shipping',
    'cost_shipping', 'etd_shipping', 'no_resi',
    'rating', 'review', 'drawn', 'order_id',
    'penjual_id', 'product_id'
  ];

  public function user(){
      return $this->belongsTo('App\Models\User');
  }

  public function order(){
      return $this->belongsTo('App\Models\Order');
  }

  public function product(){
      return $this->belongsTo('App\Models\Product');
  }

}
