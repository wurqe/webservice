<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceMeta extends Model
{
  protected $fillable = ['service_id', 'name', 'value'];

  protected $valid_names = [ 'timeframe', 'availability' ];

  public function addMetas($metas, $name, $value, $callback = false){
    $metas = $user->load(['metas' => function($q) use($name){
      $q->where('name', $name)->latest();
    }]);

    if (count($metas->metas) > 0) {
        $option = $metas->metas->first();
        $option->value = $value;
        $option->save();
    } else {
      $user->metas()->create([
        'name' => $name,
        'value' => $value,
      ]);
    }
    if($callback) $callback($user);
    return $user->myDetails();
  }

  public function service(){
    return $this->belongsTo(Service::class);
  }
}
