<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceApplication extends Model
{
  protected $fillable = ['service_id', 'user_id', 'comment', 'status'];

  public function service(){
    return $this->belongsTo(Service::class);
  }

  public function applicant(){
    return $this->belongsTo(User::class);
  }
}
