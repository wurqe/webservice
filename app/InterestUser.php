<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InterestUser extends Pivot
{
  protected $hidden = ["pivot"];
}
