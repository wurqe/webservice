<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Rules\Latitude;
use App\Rules\Longitude;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function register(Request $request){
      $request->validate([
        'email'       => 'bail|email|unique:users|max:60',
        'name'        => 'unique:users|max:100|min:3',
        'firstname'   => 'max:29|min:3',
        'lastname'    => 'max:29|min:3',
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
      $firstname      = $request->firstname;
      $lastname       = $request->lastname;

      $user = User::create([
        'email'       => $email,
        'name'        => $name,
        'password'    => Hash::make($password),
        'lat'         => $lat,
        'lng'         => $lng,
        'firstname'   => $firstname,
        'lastname'    => $lastname,
      ]);

      if ($user) {
        $token        =  $user->grantMeToken();

        return ['status'=>true,
          'message' =>trans('messages.user.created'),
          'user'=>$user,
          'token'       => $token['token'],
          'token_type'  => $token['token_type'],
          'expires_at'  => $token['expires_at']
        ];
      }

      return ['status' => false, 'message' => trans('messages.errors.unkown')];
      // $stoken = $request->get('token');
      // $user = Socialite::driver('google')->userFromToken($stoken);
      //
      // if the user exist
      // $token = $exist->createToken('')->accessToken;
      //
      // else create the user from the user object and than
      // $token = $new->createToken('')->accessToken;
      //
      // $stoken => social access token
      // $token => passport access token

      // $user = User::where('email', $email)->orWhere('name', $name)->first();

      // if ($user) {
      //   // code...
      // }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
