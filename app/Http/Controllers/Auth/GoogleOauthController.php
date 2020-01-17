<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Socialite;

class GoogleOauthController extends Controller
{
    
/**
     * Redirect the user to the google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        // enter redirect url here
        $url = "http://localhost:8000";

        $user = Socialite::driver('google')->stateless()->user();
        if($user->token){
        $checkIfUserPresentInDb = User::where('email',$user->getEmail())->first();
        if($checkIfUserPresentInDb){
        // if user already exists
        $token = $checkIfUserPresentInDb->createToken('authToken')->accessToken;
        return response(['message'=>'success','token'=>$token]);
        }else{
            // if user does not exists
         $createUser = User::create([
             'name'=>$user->getName(),
             'email'=>$user->getEmail(), 
         ]);
         $accessToken = $createUser->createToken('authToken')->accessToken;
         return response(['message'=>'success','token'=>$accesstoken]);
        }
        }
    }

}
