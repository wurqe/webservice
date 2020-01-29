<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Storage;
use Validator;

class UserController extends Controller
{

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
      $get_and_saveimage = $user->saveImage($request->photo,"avatar");
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
        //
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
