<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  /**
   * Fields that are mass assignable
   *
   * @var array
   */
  protected $fillable = [
    'bank', 'gross_amount',
    'total_cost_shipping','order_id',
    'payment_type','pdf_url',
    'transaction_status', 'va_number',
    'user_id'
  ];

  public function user(){
      return $this->belongsTo('App\Models\User');
  }

  public function order_items(){
      return $this->hasMany('App\Models\OrderItem');
  }

  /**
  * Set status to Pending
  *
  * @return void
  */
 public function setPending()
 {
     $this->attributes['transaction_status'] = 'pending';
     self::save();
 }

 /**
  * Set status to Success
  *
  * @return void
  */
 public function setSuccess()
 {
     $this->attributes['transaction_status'] = 'success';
     self::save();
 }

 /**
  * Set status to Failed
  *
  * @return void
  */
 public function setFailed()
 {
     $this->attributes['transaction_status'] = 'failed';
     self::save();
 }

 /**
  * Set status to Expired
  *
  * @return void
  */
 public function setExpired()
 {
     $this->attributes['transaction_status'] = 'expired';
     self::save();
 }

}
