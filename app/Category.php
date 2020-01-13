<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['name'];

  public function services(){
    return $this->hasMany(Service::class);
  }

  // public function categorized(){
  //   return $this->HasMany();
  // }
}
