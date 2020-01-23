<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
  protected $fillable = ['user_id', 'authorization_code', 'bin', 'last4', 'exp_month', 'exp_year', 'channel', 'card_type', 'bank', 'country_code', 'brand', 'reusable', 'signature'];

  public function owner(){
    return $this->belongsTo(User::class);
  }
}
