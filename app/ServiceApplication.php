<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceApplication extends Model
{
  protected $fillable = ['service_id', 'receiver_id', 'user_id', 'comment', 'status'];
  protected $casts = ['user_id' => 'int', 'receiver_id' => 'int', 'service_id' => 'int'];

  public function service(){
    return $this->belongsTo(Service::class);
  }

  public function receiver(){
    return $this->belongsTo(User::class, 'receiver_id');
  }

  public function applicant(){
    return $this->belongsTo(User::class, 'user_id');
  }
}
