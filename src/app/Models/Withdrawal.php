<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
  use HasFactory;
    /**
     * Fields that are mass assignable
     *
     * @var array
     */
  protected $fillable = ['nominal','status','user_id'];

  public function user(){
      return $this->belongsTo('App\Models\User');
  }

}
