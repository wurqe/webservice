<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Storage;
use Validator;
use App\UserMeta;

class UpdatePersonalInfoController extends Controller
{
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = new User();
        $profile = $user->find($id);
        $about = $profile->metas()->where('user_id',$profile->id)->where('name','about')->first();
        $quote = $profile->metas()->where('user_id',$profile->id)->where('name','quote')->first();
        $profileImage = $profile->metas()->where('user_id',$profile->id)->where('name','profileImage')->first();
        $photoUrl = url('/images/ProfilePics',$profileImage->value);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function ProfileImage(Request $request,$id)
    { 
        $user = User::find($id);
        if($user){   
    $validate = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);
       if(!empty($request->file('photo'))){   
       $file = $request->file('photo');
       $FileName = str_replace(' ', '',time().'_'.$file->getClientOriginalName());
       $path = $request->file('photo')->move(public_path("images/ProfilePics"),$FileName);
       $photoUrl = url('/ProfilePics',$FileName);

       $checkIfmetaExists = UserMeta::where('user_id',$id)->where('name','profileImage')->count(); 
       if($checkIfmetaExists > 0){
        $metadetails = UserMeta::where('user_id',$id)->where('name','profileImage')->first();
        $oldimage = $metadetails->value;
        $UpdateAboutmeta = UserMeta::whereId($metadetails->id)->update([
            "value" => $FileName]);
           Storage::delete("ProfilePics/".$oldimage); 
            return response(['message' => 'success']);
       }else{
        $createmeta = UserMeta::create([
            "user_id" => $id,"name"=>"profileImage","value" => $FileName
           ]);
           return response(['message' => 'success']);
       }
       }
    }else{
        return response(['message' => "user id doesn't exist"]);   
       }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    $user = User::find($id);
    if($user){

    $validate = $request->validate([
    "name" => 'string',
    "firstname" => 'string',
    "lastname" => 'string',
    "quote" => 'string',
    "About" => "string"
    ]);     
    
    $UpdatePersonalInfo = User::whereId($id)->update([
     "name" => $request->name,
     "firstname" => $request->firstname,
     "lastname" => $request->lastname  
    ]);
    
    $checkIfaboutmetaExists = UserMeta::where('user_id',$id)->where('name','about')->count();  
    $checkIfquotemetaExists = UserMeta::where('user_id',$id)->where('name','quote')->count();  

    if($checkIfaboutmetaExists > 0){
    $metadetails = UserMeta::where('user_id',$id)->where('name','about')->first();
    $UpdateAboutmeta = UserMeta::whereId($metadetails->id)->update([
            "value" => $request->about]);  
    }else{
    $createaboutmeta = UserMeta::create([
        "user_id" => $id,"name"=>"about","value" => $request->about
       ]);
    }
    

    if($checkIfquotemetaExists > 0){
        $metadetails = UserMeta::where('user_id',$id)->where('name','quote')->first();
        $UpdateAboutmeta = UserMeta::whereId($metadetails->id)->update([
                "value" => $request->quote]); 
    }else{
        $createquotemeta = UserMeta::create([
            "user_id" => $id,"name"=>"quote","value" => $request->quote
            ]);    
    }

    if($UpdatePersonalInfo){
     return response(['message' => 'success']);   
    }else{
    return response(['message' => 'failed']);    
    }

    }else{
     return response(['message' => "user id doesn't exist"]);   
    }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}