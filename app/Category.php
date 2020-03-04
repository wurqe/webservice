<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use App\Traits\HasImage;

class Category extends Model implements HasMedia
{
  use HasMediaTrait, HasImage;
  protected $fillable = ['name'];
  protected $hidden = ['media'];

  public function services(){
    return $this->hasMany(Service::class);
  }

  public function categories(){
    return $this->hasMany(Category::class);
  }

  public function category(){
    return $this->belongsTo(Category::class);
  }

  public function registerMediaCollections(Media $media = null){
    $this->addMediaCollection('avatar')->singleFile()
    ->useDisk('category_avatars')
    ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
    ->registerMediaConversions(function(Media $media = null){
      $this->addMediaConversion('thumb')
      ->width(100)->height(100);
    });
  }

  // public function categorized(){
  //   return $this->HasMany();
  // }
}
