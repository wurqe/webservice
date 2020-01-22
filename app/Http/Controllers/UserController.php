<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Storage;
use Validator;
use App\UserMeta;

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
    public function getprofile($id){
        $user = new User();
        $profile = $user->find($id);
        $about = $profile->metas()->where('user_id',$profile->id)->where('name','about')->first();
        $quote = $profile->metas()->where('user_id',$profile->id)->where('name','quote')->first();
        $profileImage = $profile->metas()->where('user_id',$profile->id)->where('name','profileImage')->first();
        $photoUrl = url('/storage/ProfilePics',$profileImage->value);
        return response([
        'id' => $profile->id,    
        'name'=>$profile->name,
        'firstname'=>$profile->firstname,
        'lastname' => $profile->lastname,
        'email' => $profile->email, 
        'aboutUser' => $about->value,
        'UserQuote' => $quote->value,
        'image'=>$photoUrl
        ]);
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
