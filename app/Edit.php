<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Edit extends Model
{
  protected $fillable = ['editing_type', 'editor_id', 'editor_id', 'editor_type', 'moderator_id', 'moderator_type', 'changes', 'status'];
  protected $casts = ['changes' => 'array'];

  public function metable(): MorphTo{
    return $this->morphTo();
  }
}
