<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
  protected $fillable = ['hired', "user_id", "service_id", "comment", 'status'];

  public function isWorkStarted() {
    return $this->work;
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

  public function service(){
    return $this->BelongsTo(Service::class);
  }

  public function work(){
    return $this->hasOne(Work::class);
  }
}
