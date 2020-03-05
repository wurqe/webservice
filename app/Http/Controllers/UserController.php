<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Storage;
use Validator;

class UserController extends Controller
{
  public function services(Request $request)
  {
    $request->validate([
      'type'          => ['regex:(seek|provide)'],
      'search'        => '',
      'category_id'   => 'int',
      'price_range'   => 'array',
      'extras'        => 'array',
      'orderBy'       => ['regex:(title|description|type|payment_type|amount)'],
      'order'         => ['regex:(asc|desc)'],
      'pageSize'      => 'numeric',
    ]);

    $user           = $request->user('api');
    $user_id        = $user ? $user->id : 0;
    $search         = $request->search;
    $price_range    = $request->price_range;
    $category_id    = $request->category_id;
    $extras         = $request->extras;
    $orderBy        = $request->orderBy ?? 'id';
    $pageSize       = $request->pageSize;
    $order          = $request->order ?? 'asc';
    $type           = $request->type ?? 'seek';

    $services = $user->services()->with(['skills', 'category:id,name']);

    if ($search) $services->where(function($q) use($search){
      $q->where('title', 'LIKE', '%'.$search.'%')->orWhere('title', 'description', '%'.$search.'%')
      ->orWhere('amount', 'LIKE', '%'.$search.'%')->orWhere('type', 'LIKE', '%'.$search.'%')
      ->orWhere('payment_type', 'LIKE', '%'.$search.'%');
    });
    else $services->where('type', $type);

    if($price_range) $services->whereBetween('amount', $price_range);
    if($category_id) $services->where('category_id', $category_id);

    if ($extras) {
      foreach ($extras as $key => $extra) {
        $services->when($extra == 'application_count', function($q) {
          $q->withCount('applications');
        });
      }
    }

     $services = $services->distance($user)->orderBy($orderBy, $order)->paginate($pageSize);
     foreach($services->items() as $s){
       $s->withImageUrl(null, 'attachments', true)->withRating();
     }
     // dd($services->getQueryLog());
     return $services;
  }

  public function whoami(Request $request)
  {
    return $request->user();
  }

  public function serviceStats(Request $request)
  {
    $user           = $request->user();
    return ['completionRate' => $user->jobsCompletionRate(), 'responseRate' => $user->InvitationsResponseRate(), 'compliantRate' => $user->CompliantRate()];
  }

  public function jobs(Request $request)
  {
    $request->validate([
      'orderBy'       => ['regex:(completed_at|amount|status)'],
      'order'         => ['regex:(asc|desc)'],
      'pageSize'      => 'numeric',
    ]);

    $user           = $request->user();
    $search         = $request->search;
    $orderBy        = $request->orderBy ?? 'id';
    $pageSize       = $request->pageSize;
    $order          = $request->order ?? 'asc';

    $jobs           = $user->jobs();
    if ($search) $jobs->where(function($q) use($search){
      $q->where('works.amount', 'LIKE', '%'.$search.'%')->orWhere('works.status', 'LIKE', '%'.$search.'%')->orWhere('works.amount_currency', 'LIKE', '%'.$search.'%')
      ->orWhere('works.invitation_id', 'LIKE', '%'.$search.'%')->orWhere('works.service_id', 'LIKE', '%'.$search.'%');
    });

    return $jobs->orderBy($orderBy, $order)->paginate($pageSize);
  }

  public function wallet(Request $request)
  {
    $user = $request->user();
    return ['wallet' => $user->wallet];
  }

  public function balance(Request $request)
  {
    $user = $request->user();
    return ['balance' => $user->balance];
  }

  public function Kycdocs(Request $request){
  $validate = Validator::make($request->all(),[
      "photo" => 'required|image|mimes:jpeg,png'
      ]);
  if($validate->fails()):
  return json_encode(["message"=>"file is bigger than 1mb or incorrect format, must be jpeg."]);
  endif;
  $user = User::find($request->id);
  if($user):
  $file = $request->file('photo');
  if($file):
  $FileName = str_replace(' ', '',time().'_'.$file->getClientOriginalName());
  $path = $request->file('photo')->storeAs('KYCDOCS', $FileName);
  $oldkycdocs = $user->metas()->where('name','kycdocs')->first();
  Storage::delete("KYCDOCS/".$oldkycdocs->value);
  $save = $user->addMeta(["name" => "kycdocs"],["value" => $FileName]);
  return json_encode(['message'=>"success"]);
  endif;
endif;
       //$path = $user->saveImage($file,'ProfilePics');
       /*$photoUrl = url('/storage/ProfilePics',$FileName);*/
  }

   public function UserProfileUpdate(Request $request){
    $user = User::find($request->id);
    if($user):
      $get_and_saveimage = $user->saveImage($request->photo,"profilepics");
      return $get_and_saveimage;
    endif;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      return ['1'=>1];
        //
    }
        //
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      return $user->withMetas()->withRating();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
      $this->authorize('update', $user);
      $request->validate([]);
      $user         = $request->user();
      $avatar       = $request->avatar;
      $updates      = $request->all();
      $keys         = array_keys($updates);
      $metas        = ['availabilty', 'account_type', 'quote', 'gender', 'state', 'city', 'country', 'address', 'phone', 'phone_code'];
      $prop         = ['firstname', 'lastname', 'lng', 'lat'];
      $metas_arr    = array_intersect($keys, $metas);
      $prop_arr     = array_intersect($keys, $prop);

      // if user property
      if ($prop_arr) {
        $updates = [];
        foreach ($prop_arr as $key => $value) {
          $index = $prop_arr[$key];
          $updates[$index] = $request->$index;
        }
        $user->update($updates);
      }
      // if meta
      if ($metas_arr) {
        $user->addMetas($metas_arr, $request);
        $user->withMetas();
      }

      try {
        if($avatar) $user->saveImage($avatar, 'avatar');
      } catch (\Exception $e) {}
      return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
