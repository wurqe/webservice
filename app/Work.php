<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;

class Work extends Model implements ReviewRateable
{
  use ReviewRateableTrait;
  protected $fillable = ['service_id', 'completed_at', 'amount', 'amount_currency', 'payment_method'];

  public function service(){
    return $this->belongsTo(Service::class);
  }
}
