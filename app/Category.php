<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['name'];

  public function services(){
    return $this->hasMany(Service::class);
  }

  public function categories(){
    return $this->hasMany(Category::class);
  }

  public function category(){
    return $this->belongsTo(Category::class);
  }

  // public function categorized(){
  //   return $this->HasMany();
  // }
}
