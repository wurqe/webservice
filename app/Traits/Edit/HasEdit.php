<?php
namespace App\Traits\Edit;

use App\Edit;
/**
 *
 */
trait HasEdit
{
  public function edits(){
    return $this->morphMany(Edit::class, 'edit');
  }
  public function edited(){
    return $this->morphOne(Edit::class, 'edit')->where('status', 'accepted')->latest();
  }
}
