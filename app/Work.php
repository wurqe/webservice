<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Codebyray\ReviewRateable\Models\Rating;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Product;
use Bavix\Wallet\Interfaces\Customer;

class Work extends Model implements ReviewRateable, Product
{
  use ReviewRateableTrait, HasWallet;
  protected $fillable = ['status', 'invitation_id', 'service_id', 'completed_at', 'amount', 'amount_currency', 'payment_method'];

  public function canBuy(Customer $customer, int $quantity = 1, bool $force = null): bool
  {
      /**
       * If the service can be purchased once, then
       *  return !$customer->paid($this);
       */
      return true;
  }

  public function getAmountProduct(Customer $customer): int
  {
      return $this->amount ?? 0;
  }

  public function getMetaProduct(): ?array
  {
      return [
          'title' => $this->service->title,
          'description' => 'Payment for Service #' . $this->service->id,
      ];
  }

  public function getUniqueId(): string
  {
      return (string)$this->getKey();
  }

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
