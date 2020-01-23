<?php
namespace App\Traits\Edit;

use App\Edit;
/**
 *
 */
trait HasModerator
{
  public function moderate(Edit $moderate, string $status) : Edit{
    if ($this->canModerateThis($moderate)) {
      $moderate->update(['status' => $status]);
      return $moderate;
    } else {
      return $moderate;
    }
  }

  private function canModerateThis(Edit $moderate){
    return $moderate->moderator_id == $this->getKey();
  }

  public function moderates(){
    return $this->morphMany(Edit::class, 'moderator');
  }

  public function moderated(){
    return $this->morphMany(Edit::class, 'moderator')->where('status', '!=', 'pending');
  }
}
