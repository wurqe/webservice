<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Socialite;


class FacebookOauthController extends Controller
{

 /**
     * Redirect the user to the facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        // enter redirect url here
        $url = "http://localhost:8000";

        $user = Socialite::driver('facebook')->stateless()->user();
        if($user->token){
        $checkIfUserPresentInDb = User::where('email',$user->getEmail())->first();
        if($checkIfUserPresentInDb){
        // if user already exists
        $token = $checkIfUserPresentInDb->createToken('authToken')->accessToken;
        return redirect()->to($url.'?userId='.$checkIfUserPresentInDb->id.'token='.$token);
        }else{
            // if user does not exists
         $createUser = User::create([
             'name'=>$user->getName(),
             'email'=>$user->getEmail(),
         ]);
         $accessToken = $createUser->createToken('authToken')->accessToken;
         return redirect()->to($url.'?token='.$accessToken.'userId='.$createUser->id); //redirect to users dashboard;
        }
        }
    }


}
