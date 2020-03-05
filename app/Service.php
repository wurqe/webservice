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
use App\Collections\ServiceCollection;
use Codebyray\ReviewRateable\Models\Rating;

class Service extends Model implements HasMedia, Editable
{
  use \Spatie\Tags\HasTags, HasMediaTrait, HasMeta, HasImage, HasEdit;

  protected $fillable   = ['type', 'tags', 'title', 'description', 'amount', 'payment_type', 'negotiable', 'terms', 'user_id', 'category_id'];

  protected $hidden     = ['pivot', 'media'];
  protected $mediaNames = ['attachment' => 'attachments'];
  protected $casts      = ['negotiable' => 'boolean', 'amount' => 'float', 'user_id' => 'int', 'category_id' => 'int'];

  public static function scopeDistance($query, $user = null){
    if ($user) {
      $latitude   = $user->lat;
      $longitude  = $user->lng;
      $latName    = 'lat';
      $lonName    = 'lng';
      $calc       = 1.1515 * 1.609344;

      $sql = "((ACOS(SIN($latitude * PI() / 180) * SIN($latName * PI() / 180) + COS($latitude * PI() / 180) * COS($latName * PI() / 180) * COS(($longitude - $lonName ) * PI() / 180)) * 180 / PI()) * 60 * $calc) as distance";
      $query->with(['user' => function($q) use($sql){
        $q->selectRaw("id,".$sql);
      }]);
      return $query;
    }
  }

  public function withAvgRating()
  {
    return $this->load(['ratings']);
  }

  public function reviews()
  {
    $jobs           = $this->works()->pluck('works.id');
    return \Codebyray\ReviewRateable\Models\Rating::where('reviewrateable_type', Work::class)->whereIn('reviewrateable_id', $jobs);
  }

  public function withRating(){
    $ratings            = $this->reviews()->selectRaw('rating');
    $quantity           = $ratings->count();
    $total              = $ratings->sum('rating');
    $this->avgRating    = $this->calcAvgRating($total, $quantity);
    $this->ratingCount  = $quantity;
    return $this;
  }

  public function calcAvgRating($total, $quantity, $max = 5)
  {
    return $total ? $total/$quantity : 0;
  }

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

  public function works(){
    return $this->hasMany(Work::class);
  }

  public function newCollection(array $services = [])
  {
    return new ServiceCollection($services);
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
