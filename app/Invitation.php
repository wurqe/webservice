<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
  protected $fillable = ["user_id", "service_id", "comment", 'status'];

  public function user(){
    return $this->BelongsTo(User::class);
  }

  public function service(){
    return $this->BelongsTo(Service::class);
  }
}
