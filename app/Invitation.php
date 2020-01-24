<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Edit\HasEdit;
use App\Traits\Bid\HasBid;
use App\Interfaces\Edit\Editable;

class Invitation extends Model implements Editable
{
  use HasEdit, HasBid;
  protected $fillable = ['hired', "user_id", "receiver_id", "service_id", "comment", 'status'];
  // protected $hidden = ['edits'];

  public function isWorkStarted() {
    return $this->work;
  }

  public function afterBid($bid_action)
  {
    if($bid_action == 'accepted'){
      if($this->isAccepted() && $this->hasInitiatedContract()){
        return Work::startWork($this);
      }
      if(!$this->isAccepted()) {
        $this->accept();
        return $this;
      }
    }
  }

  public function otherBider($moderator, $user, $bid = null){
    if($moderator)  return $moderator;
    if($bid)        return $bid->editor;
    if($user)       return $user;
    return $this->user_id == $user->id ? $this->receiver : $this->user;
  }

  public function isSender(User $user) {
    return $this->user_id == $user->id;
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

  public function hasInitiatedContract() {
    return $this->hired;
  }

  public function isAccepted() {
    return $this->status == 'accepted';
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
