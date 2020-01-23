<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Edit\HasEdit;
use App\Interfaces\Edit\Editable;

class Invitation extends Model implements Editable
{
  use HasEdit;
  protected $fillable = ['hired', "user_id", "receiver_id", "service_id", "comment", 'status'];
  // protected $hidden = ['edits'];

  public function isWorkStarted() {
    return $this->work;
  }

  public function attemptBid(User $moderator, $bid, $bid_action, $bid_amount = null){
    if($bid) $moderator->moderate($bid, $bid_action);
    if($bid_action == 'accepted') $this->accept();
    if($bid_amount && $bid_action != 'accepted') $moderator->bid($this, $this->otherBider($moderator, $bid), $bid_amount);
    $this->loadBids();
    return $this;
  }

  public function otherBider($user, $bid = null){
    if($bid) return $bid->editor;
    return $this->user_id == $user->id ? $this->receiver : $this->user;
  }

  public function isSender(User $user) {
    return $this->user_id == $user->id;
  }

  public function loadBids(){
    $this->bids = $this->edits;
    $this->makeHidden(['edits']);
    return $this;
  }

  public function accept(){
    $this->update(['status' => 'accepted']);
    return $this;
  }

  public function cancel(){
    $this->update(['status' => 'canceled']);
    return $this;
  }

  public function reject(){
    $this->update(['status' => 'rejected']);
    return $this;
  }

  public function attempt(string $status){
    if($status == 'accepted') $this->accept();
    if($status == 'rejected') $this->reject();
    if($status == 'canceled') $this->cancel();
    return $this;
  }

  public function initaiteContract() {
    return $this->update(['hired' => 1]);
  }

  public function isAccepted() {
    return $this->status == 'accepted';
  }

  public function bids(){
    return $this->edits();
  }

  public function user(){
    return $this->BelongsTo(User::class);
  }

  public function receiver(){
    return $this->BelongsTo(User::class, 'receiver_id');
  }

  public function service(){
    return $this->BelongsTo(Service::class);
  }

  public function work(){
    return $this->hasOne(Work::class);
  }
}
