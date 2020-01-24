<?php
namespace App\interfaces\Edit;

interface CanModerate
{
  public function willModerates() : array;
}
