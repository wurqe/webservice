<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Codebyray\ReviewRateable\Models\Rating;

class Work extends Model implements ReviewRateable
{
  use ReviewRateableTrait;
  protected $fillable = ['status', 'invitation_id', 'service_id', 'completed_at', 'amount', 'amount_currency', 'payment_method'];

  public function isRated(){
    return $this->rated;
  }

  public function rateArray($rating, $title = null, $body = null){
    return [
      'rating'    => $rating,
      'title'     => $title,
      'body'      => $body,
      'approved'  => true,
    ];
  }

  public function isCompleted(){
    return $this->status == 'completed';
  }
  public function complete(){
    return $this->update(['status' => 'completed', 'completed_at' => now()]);
  }

  public function invitation(){
    return $this->belongsTo(Invitation::class);
  }

  public function rated(){
    return $this->morphOne(Rating::class, 'reviewrateable');
  }

  public function service(){
    return $this->belongsTo(Service::class);
  }
}
