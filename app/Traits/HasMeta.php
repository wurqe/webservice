<?php
namespace App\Traits;

/**
 *
 */
trait HasMeta
{
  public function addMeta($check = [], $metas, $load = true){
    $meta = $this->metas()->updateOrCreate($check, $metas);
    if($load) $this->load('metas');
    return $meta;
  }

  public function addMetas($metas_arr, $request, $callback = false){
    foreach ($metas_arr as $key => $value) {
      $index = $metas_arr[$key];
      $update = ['name' => $index, 'value' => $request->$index];
      $this->addMeta($update, $update, false);
    }

    // $mts = [];
    // foreach ($metass as $metas) {
    //   $met = ['name' => 'timeframe', 'value' => $request->timeframe];
    //   $mts[] = $this->addMeta($metas['check'], $metas['metas']);
    // }
    // return $mts;
  }

  public function updateOrCreateMany()
  {

  }
}
