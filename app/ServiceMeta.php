<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceMeta extends Model
{
  protected $fillable = ['service_id', 'name', 'value'];

  public function service(){
    return $this->belongsTo(Service::class);
  }
}
