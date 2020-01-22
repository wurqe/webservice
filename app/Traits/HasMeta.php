<?php
namespace App\Traits;

/**
 *
 */
trait HasMeta
{
  public function addMeta($check = [], $metas){
    $meta = $this->metas()->updateOrCreate($check, $metas);
    $this->load('metas');
    return $meta;
  }

  public function addMetas($metas, $name, $value, $callback = false){
    $metas = $this->load(['metas' => function($q) use($name){
      $q->where('name', $name)->latest();
    }]);

    if (count($metas->metas) > 0) {
        $option = $metas->metas->first();
        $option->value = $value;
        $option->save();
    } else {
      $this->metas()->create([
        'name' => $name,
        'value' => $value,
      ]);
    }
    if($callback) $callback($this);
    return $this;
  }
}
