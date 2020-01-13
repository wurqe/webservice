<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use \Spatie\Tag;

class Service extends Model implements HasMedia
{
  use \Spatie\Tags\HasTags, HasMediaTrait;

  protected $fillable = ['title', 'description', 'amount', 'payment_type', 'negotiable', 'terms', 'user_id', 'category_id', 'timeframe'];

  public function skills(){
    return $this->belongsToMany(Tag::class, 'taggables');
  }

  public function user(){
    return $this->belongsTo(User::class);
  }

  public function category(){
    return $this->belongsTo(Category::class);
  }

  public function invitaions(){
    return $this->hasMany(Invitation::class);
  }

  public function applications(){
    return $this->hasMany(ServiceApplication::class);
  }

  public function metas(){
    return $this->hasMany(ServiceMeta::class);
  }

  public function work(){
    return $this->hasOne(Work::class);
  }
}
