<?php
namespace App\Traits;

/**
 *
 */
trait HasMeta
{
  public function addMeta($check = [], $metas, $load = true){
    $meta = $this->metas()->updateOrCreate($check, $metas);
    if($load) $this->withMetas();
    return $meta;
  }

  public function addMetas($metas_arr, $request, $callback = false){
    foreach ($metas_arr as $key => $value) {
      $index = $metas_arr[$key];
      $update = ['name' => $index, 'value' => $request->$index];
      $this->addMeta($update, $update, false);
    }
  }

  public function withMetas()
  {
    $metas = $this->metas;
    $userMeta = [];
    $metas->map(function($meta) use(&$userMeta){
      $userMeta[$meta->name] = $meta->value;
    });
    // $userMeta['verifiedEmail'] = true;
    // $userMeta['verifiedPhone'] = true;
    // $userMeta['verifiedIdentity'] = true;
    // $userMeta['verifiedAddress'] = true;
    $this->meta = $userMeta;
    $this->makeHidden(['metas']);
    return $this;
  }
}
