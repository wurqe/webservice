<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\CanPay;
use Bavix\Wallet\Interfaces\Customer;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use Bavix\Wallet\Interfaces\Taxable;
use App\Traits\HasMeta;
use App\Traits\HasImage;

class User extends Authenticatable implements Wallet, Customer, HasMedia, Taxable
{
  use Notifiable, CanPay, HasMediaTrait, HasApiTokens, HasMeta, HasImage;

  public function addSetting($name, $value){
    $meta = $this->settings()->updateOrCreate(['name' => $name], ['name' => $name, 'value' => $value]);
    $this->load('settings');
    return $meta;
  }

  // public function addSetting($metas){
  //   $meta = $this->settings()->updateOrCreate($metas);
  //   $this->load('settings');
  //   return $meta;
  // }

  public function getFeePercent() : float{
    return 1;//1%
  }

  public function rate(Work $work, array $rating){
    return $work->rating($rating, $this);
  }

  public function invite(Service $service){
    return $this->invitaions()->create([
      'service_id'    => $service->id,
    ]);
  }

  public function hasPendingApp($service)
  {
    return $this->pending_applications()->where('service_id', $service->id)->first();
  }

  public function grantMeToken(){
    $token          =  $this->createToken('MyApp');

    return [
      'token'       => $token->accessToken,
      'token_type'  => 'Bearer',
      'expires_at'  => Carbon::parse(
          $token->token->expires_at
      )->toDateTimeString(),
    ];
  }

  public function hire_invitations(){
    return $this->hasManyThrough(Invitation::class, Service::class);
  }

  public function payment_options(){
    return $this->hasMany(PaymentOption::class);
  }

  public function invitaions(){
    return $this->hasMany(Invitation::class, 'user_id');
  }

  public function received_invitaions(){
    return $this->hasManyThrough(Invitation::class, Service::class);
  }

  public function pending_invitaions(){
    return $this->invitaions()->where('status', 'pending');
  }

  public function payments(){
    return $this->hasMany(Payment::class);
  }

  public function services(){
    return $this->hasMany(Service::class);
  }

  public function applications(){
    return $this->hasMany(ServiceApplication::class);
  }

  public function pending_applications(){
    return $this->applications()->where('status', 'pending');
  }

  public function settings(){
    return $this->hasMany(Setting::class);
  }

  public function metas(){
    return $this->morphMany(Meta::class, 'metable');
  }

  public function notifications(){
    return $this->morphMany(Notification::class, 'notifiable')->latest();
  }

  public function registerMediaCollections(Media $media = null){
    $this->addMediaCollection('avatar')
    ->useDisk('user_avatars')
    ->acceptsMimeTypes(['image/jpeg', 'image/png'])
    ->registerMediaConversions(function(Media $media = null){
      $this->addMediaConversion('thumb')
      ->width(100)->height(100);
    });
  }

  // public function works(){
  //   return $this->hasMany(UserMeta::class);
  // }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password', 'firstname', 'lastname', 'lng', 'lat'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
      'email_verified_at' => 'datetime',
  ];
}
