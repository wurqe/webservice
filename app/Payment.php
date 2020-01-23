<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $fillable = ['access_code', 'user_id', 'message', 'amount', 'status', 'reference', 'authorization_code', 'currency_code', 'payed_at'];

  public function user(){
    return $this->belongsTo(User::class);
  }
}
