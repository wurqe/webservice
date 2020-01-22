<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
  protected $fillable = ['name', 'value'];
  protected $valid_names = [ 'timeframe', 'availability' ];

  public function metable(): MorphTo{
    return $this->morphTo();
  }
}
