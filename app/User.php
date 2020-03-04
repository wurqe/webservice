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
use App\Traits\Edit\HasEditor;
use App\Traits\Edit\HasModerator;
use App\Interfaces\Edit\CanModerate;
use App\Interfaces\Edit\CanEdit;
use App\Traits\Bid\HasBid;
use App\Notifications\Work\NewReview;
use App\Notifications\Wallet\WalletUpdate;
use App\Collections\UserCollection;

class User extends Authenticatable implements Wallet, Customer, HasMedia, Taxable, CanEdit, CanModerate
{
  use Notifiable, CanPay, HasMediaTrait, HasApiTokens, HasMeta, HasImage, HasModerator, HasEditor, HasBid;

  // public function bid($invitation, $otherUser, int $amount){
  //   // check if user trying to make bid on pending invitation bid
  //   if ($this->hasPendingEditFor($invitation)) return null;
  //
  //   return $this->edit($invitation, ['amount' => $amount], 'price', $otherUser);
  // }

  public function willEdits() : array {return [Service::class, Invitation::class, Work::class];}
  public function willModerates() : array {return [Service::class, Invitation::class, Work::class];}

  public function pay4Job(Work $work)
  {
    $otherUser    = $work->service->user;
    $per_amount   = $work->calculateAmount();

    $transfer = $work->transfer($otherUser, $per_amount);
    $name = "{$this->firstname} {$this->lastname}";
    $otherName = "{$otherUser->firstname} {$otherUser->lastname}";

    $otherUser->notify(new WalletUpdate('received', $name, $otherName, $per_amount, $work->id, $work->service->id));
    $this->notify(new WalletUpdate('sent', $otherName, $name, $per_amount, $work->id, $work->service->id));

    return $transfer;
  }

  public function addSetting($name, $value){
    $meta = $this->settings()->updateOrCreate(['name' => $name], ['name' => $name, 'value' => $value]);
    $this->load('settings');
    return $meta;
  }

  public function getFeePercent() : float{
    return 1;//1%
  }

  public function rate(Work $work, array $rating){
    $user = $work->invitation->receiver;
    $review = $work->rating($rating, $this);
    $user->notify(new NewReview($review, $work, $user, $work->invitation));
    return $review;
  }

  public function invite(Service $service, User $otherUser, $bid_amount = null){
    $invitation           = $this->invitations()->create([
      'service_id'        => $service->id,
      'receiver_id'       => $otherUser->id,
    ]);

    if($invitation){
      $invitation->attemptBid($this, null, null, $bid_amount, $otherUser);
    }

    return $invitation;
  }

  public function afterBid($bid_action)
  {
    if($bid_action == 'accepted') $this->accept();
  }

  public function otherBider($moderator, $user, $bid = null){
    if($moderator) return $moderator;
    if($bid) return $bid->editor;
    return $user;
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

  public function invitations(){
    return $this->hasMany(Invitation::class, 'user_id');
  }

  public function received_invitations(){
    return $this->hasMany(Invitation::class, 'receiver_id');
  }

  public function pending_invitations(){
    return $this->invitations()->where('status', 'pending');
  }

  public function payments(){
    return $this->hasMany(Payment::class);
  }

  public function services(){
    return $this->hasMany(Service::class);
  }

  public function pending_applications(){
    return $this->applications()->where('status', 'pending');
  }

  public function received_applications(){
    return $this->hasMany(ServiceApplication::class, 'receiver_id');
  }

  public function applications(){
    return $this->hasMany(ServiceApplication::class);
  }

  public function settings(){
    return $this->hasMany(Setting::class);
  }

  public function jobs(){
    return $this->hasManyThrough(Work::class, Invitation::class, 'receiver_id');
  }

  public function metas(){
    return $this->morphMany(Meta::class, 'metable');
  }

  public function registerMediaCollections(Media $media = null){
    $this->addMediaCollection('avatar')->singleFile()
    ->useDisk('user_avatars')
    ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
    ->registerMediaConversions(function(Media $media = null){
      $this->addMediaConversion('thumb')
      ->width(100)->height(100);
    });
  }

  public function newCollection(array $users = [])
  {
    return new UserCollection($users);
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
      'email_verified_at',
      'media', 'pivot'
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
