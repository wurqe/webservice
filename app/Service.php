<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
// use Spatie\Tags\Tag;
use \getID3;
use App\Media as MMedia;
use App\Traits\HasMeta;
use App\Traits\HasImage;
use App\Traits\Edit\HasEdit;
use App\Interfaces\Edit\Editable;

class Service extends Model implements HasMedia, Editable
{
  use \Spatie\Tags\HasTags, HasMediaTrait, HasMeta, HasImage, HasEdit;

  protected $fillable   = ['type', 'tags', 'title', 'description', 'amount', 'payment_type', 'negotiable', 'terms', 'user_id', 'category_id'];

  protected $hidden     = ['pivot'];
  protected $mediaNames = ['attachment' => 'attachments'];

  public function calculateAmount()
  {
    $amount       = $this->amount;
    $per          = $amount * (1 / 100);
    $per_amount   = $amount - $per;
    return $per_amount;
  }

  public function skills(){
    return $this->belongsToMany(Tag::class, 'taggables', 'taggable_id');
  }

  public function isWorkStarted() {
    return $this->work;
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

  public function isSeeking(){
    return $this->type == 'seek';
  }

  public function applications(){
    return $this->hasMany(ServiceApplication::class);
  }

  public function metas(){
    return $this->morphMany(Meta::class, 'metable');
  }

  public function work(){
    return $this->hasOne(Work::class);
  }

  public function registerMediaCollections(Media $media = null){
    $this->addMediaCollection('attachments')
    ->useDisk('service_attachments')
    // ->useFallbackUrl('/images/anonymous-user.jpg')
    //     ->useFallbackPath(public_path('/images/anonymous-user.jpg'))
    ->acceptsMimeTypes(['image/jpeg', 'image/png'])
    ->registerMediaConversions(function(Media $media = null){
      $this->addMediaConversion('thumb')
      ->width(100)->height(100);
      // ->performOnCollections('attachments');//->sharpen(10)
      // ->withResponsiveImages()
    });
    // ->singleFile()
    // ->onlyKeepLatest(3)
  }
}
