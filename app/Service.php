<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use \Spatie\Tag;
use \getID3;
use App\Media as MMedia;

class Service extends Model implements HasMedia
{
  use \Spatie\Tags\HasTags, HasMediaTrait;

  protected $fillable = ['type', 'tags', 'title', 'description', 'amount', 'payment_type', 'negotiable', 'terms', 'user_id', 'category_id'];

  public function addMeta($metas){
    $meta = $this->metas()->updateOrCreate($metas);
    $this->load('metas');
    return $meta;
  }

  public function saveImage($image){
    $attachments = [];
    if (\is_array($image)) foreach ($image as $img) $attachments[] = $this->uploadImage($img);
    else $attachments[] = $this->uploadImage($image);
    return $this->withAttachmentsUrl($attachments);
  }

  public function uploadImage($image){
    $collection = 'attachments';
    $name = $collection;
    $type = strpos($image, ';');
    $type = explode(':', substr($image, 0, $type))[1];
    $ext = explode('/', $type)[1];
    $file_name = rand().'.'.$ext;

    return $this->addMediaFromBase64($image)
    ->usingName($name)->usingFileName($file_name)
    ->toMediaCollection($collection);
  }

  public function withAttachmentsUrl($medias = null){
    if (!$medias) $medias = $this->getMedia('attachments');

    if ($medias) {
      $images = [];
      for ($i=0; $i < sizeof($medias); $i++) {
        $media = $medias[$i];
        $image = new \stdClass();
        $image->thumb = $media->getUrl('thumb');
        $image->url = $media->getUrl();
        $image->metas = $media->custom_properties;
        $images[] = $image;
      }
      if ($images) $this->attachments = $images;
    }
    return $this;
  }

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
