<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  protected $fillable = ['user_id', 'name', 'value'];

  public function user(){
    return $this->belongsTo(User::class);
  }
}
