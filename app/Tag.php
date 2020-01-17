<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\Tag as Tagg;

class Tag extends Tagg
{
  protected $hidden = ['pivot', 'created_at', 'updated_at', 'type', 'slug', 'order_column'];
}
