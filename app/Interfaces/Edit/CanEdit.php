<?php
namespace App\interfaces\Edit;

interface CanEdit
{
  public function willEdits() : array;
}
