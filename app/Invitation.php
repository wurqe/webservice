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

  public function loadBids(){
    $this->bids = $this->edits;
    $this->makeHidden(['edits']);
    return $this;
  }

  public function initaiteContract() {
    return $this->update(['hired' => 1]);
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
