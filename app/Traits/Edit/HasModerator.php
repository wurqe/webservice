<?php
namespace App\Traits\Edit;

use App\Edit;
/**
 *
 */
trait HasModerator
{
  public function moderate(Edit $edit, string $status) : Edit{
    if ($this->canModerateThis($edit)) {
      $edit->update(['status' => $status]);
      return $edit;
    } else {
      return $edit;
    }
  }

  private function canModerateThis(Edit $edit){
    // dd($edit->moderator_id, $this->getKey());
    return $edit->moderator_id == $this->getKey();
  }

  public function moderates(){
    return $this->morphMany(Edit::class, 'moderator');
  }

  public function moderated(){
    return $this->morphMany(Edit::class, 'moderator')->where('status', '!=', 'pending');
  }
}
