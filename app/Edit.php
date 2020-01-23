<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Edit extends Model
{
  protected $fillable = ['editing_type', 'editor_id', 'editor_id', 'editor_type', 'moderator_id', 'moderator_type', 'name', 'changes', 'status'];
  protected $casts = ['changes' => 'array'];

  // private function accept(){
  //   $this->update(['status' => 'accepted']);
  //   return $this;
  // }
  //
  // private function cancel(){
  //   $this->update(['status' => 'canceled']);
  //   return $this;
  // }
  //
  // private function reject(){
  //   $this->update(['status' => 'rejected']);
  //   return $this;
  // }
  //
  // private function attempt(string $status){
  //   if($status == 'accepted') $this->accept();
  //   if($status == 'rejected') $this->reject();
  //   if($status == 'canceled') $this->cancel();
  //   return $this;
  // }

  public function editable(): MorphTo{
    return $this->morphTo();
  }
}
