<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Codebyray\ReviewRateable\Models\Rating;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Product;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Taxable;
use App\Traits\Edit\HasEdit;
use App\Interfaces\Edit\Editable;
use App\Traits\Bid\HasBid;

class Work extends Model implements ReviewRateable, Product, Taxable, Editable
{
  use ReviewRateableTrait, HasWallet, HasEdit, HasBid;
  protected $fillable = ['status', 'invitation_id', 'service_id', 'completed_at', 'amount', 'amount_currency'];
  protected $casts = ['invitation_id' => 'int', 'service_id' => 'int'];

  public function afterBid($bid_action)
  {
    if($bid_action == 'accepted'){
      // if(!$this->isCompleted()) {
      //   $this->complete();
      //   return $this;
      // }
    }
    return $this;
  }

  public function otherBider($moderator, $user, $bid = null){
    if($moderator) return $moderator;
    if($bid)       return $bid->editor;
    if($user)      return $user;
  }

  public static function startWork(Invitation $invitation)
  {
    $user = $invitation->user;
    if ($user->can('work', $invitation) && $user->can('create', Work::class)) {
      // check started work
      if($work        = $invitation->isWorkStarted()) return $work;

      $amount         = $invitation->acceptedBidAmount() ?? $invitation->service->amount;
      $percentage     = 99;//99% e.g
      $calculated     = $amount / (100/$percentage);

      return $invitation->work()->create([
        'service_id'        => $invitation->service_id,
        'amount'            => $calculated,
      ]);
    }
  }

  public function canBuy(Customer $customer, int $quantity = 1, bool $force = null): bool
  {
      /**
       * If the service can be purchased once, then
       *  return !$customer->paid($this);
       */
      return true;
  }

  public function loadEarnedPrice()
  {
    $this->earning = $this->calculateAmount();
  }

  public function getAmountProduct(Customer $customer = null): int
  {
    return $this->acceptedBidAmount() ?? $this->amount ?? 0;
  }

  public function calculateAmount($invitation = null)
  {
    $amount       = $this->getAmountProduct();
    if ($this->isHourlyPrice()) {
      $work_start     = new \DateTime($this->created_at);
      $work_completed = new \DateTime($this->completed_at);
      $diff           = $work_start->diff($work_completed);
      $mins           = $diff->s;
      $hrs            = $diff->f;
      $floated        = (float)"$hrs.$mins";
      $earned         = $floated * $amount;
    } else {
      $per            = $amount * (1 / 100);
      $earned         = $amount - $per;
    }
    return $earned;
  }

  public function isHourlyPrice(){
    return $this->service->payment_type == 'hourly';
  }

  public function getFeePercent() : float
  {
      return 0.01; // 1%
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
