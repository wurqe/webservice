<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\Latitude;
use App\Rules\Longitude;
use App\User;
use Illuminate\Validation\Rule;
use \Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
      $request->validate([
        'email'       => 'email|max:60',
        'name'        => 'max:100|min:3',
        'password'    => 'required',
        'lat'         => [new Latitude],
        'lng'         => [new Longitude],
      ]);

      $email          = $request->email;
      $name           = $request->name;
      if (!$email || !$name) return response()->json(['errors'=>trans('validation.either_required')], 422);
      $password       = $request->password;
      $lat            = $request->lat;
      $lng            = $request->lng;

      $user = User::where('email', $email)->orWhere('name', $name)->first();
      if ($user) {
        if(Hash::check($password, $user->password)){
          $token          =  $user->grantMeToken();

          if($lat && $lng) $user->update(['lat' => $lat, 'lng' => $lng]);

          return ['status'=> true,
            'message'     => trans('msg.user.login'),
            'user'        => $user,
            'token'       => $token['token'],
            'token_type'  => $token['token_type'],
            'expires_at'  => $token['expires_at']
          ];
        } else {
          throw ValidationException::withMessages([
            'password' => [trans('validation.password')],
          ]);
        }
      } else {
        return ['status' => false, 'message' => trans('msg.not_exist')];
      }
    }
}
