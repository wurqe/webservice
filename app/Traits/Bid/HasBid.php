<?php
namespace App\Traits\Bid;

use App\User;
use App\Interfaces\Edit\Editable;
use App\Interfaces\Edit\CanModerate;
/**
 *
 */
trait HasBid
{
  public function acceptedBid(){
    $bid = $this->edited()->first();
    if ($bid) {
      return $bid['changes'];
    } else {
      return null;
    }
  }

  public function attemptBid(User $user, $bid, $bid_action, $bid_amount = null, CanModerate $moderator = null, $name = null){
    if($bid && $bid->status != 'accepted') $user->moderate($bid, $bid_action);
    if($bid_amount && $bid_action != 'accepted') $user->bid($this, ['amount' => $bid_amount], $this->otherBider($moderator, $user, $bid), $name);
    $this->load('bids');
    return $this->afterBid($bid_action);
  }

  public function bid(Editable $biding, array $edits, CanModerate $moderator = null, $name = 'price'){
    // check if user trying to make bid on pending invitation bid
    if ($this->hasPendingEditFor($biding)) return null;

    return $this->edit($biding, $edits, $name ?? 'price', $moderator);
  }

  public function bids()
  {
    return $this->edits();
  }
}
