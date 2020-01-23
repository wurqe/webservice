<?php
namespace App\Traits\Edit;

use App\Interfaces\Edit\Editable;
use App\Interfaces\Edit\CanModerate;
use App\Edit;
/**
 *
 */
trait HasEditor
{
  public function edit(Editable $editing, array $changes, string $name = 'default', CanModerate $moderator = null){
    if ($this->canEditThis($editing)) {
      $moderator_id = $moderator ? $moderator->getKey() : null;
      $editor_id = $this->getKey();

      $moderator_class = $moderator ? get_class($moderator) : null;
      return $editing->edits()->create([
        'changes'   => $changes,   'moderator_id' => $moderator_id,
        'moderator_type' => $moderator_class, 'name' => $name,
        'editor_id' => $editor_id, 'editor_type' => get_class($this),
      ]);
    } else {
      return $editing;
    }
  }

  private function canEditThis(Editable $edit){
    $status = false;
    foreach ($this->willEdits() as $classes_string) {
      if($edit instanceof $classes_string){
        $status = true;
        break;
      }
    }
    return $status;
  }

  public function editing(){
    return $this->morphMany(Edit::class, 'editor');
  }

  public function edited(){
    return $this->morphMany(Edit::class, 'editor')->where('status', 'accepted')->latest();
  }
}
