<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider) 
    { 
      Socialite::driver($provider)->redirect(); 
    }
/**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
            $user = Socialite::driver($provider)->stateless()->user();
            var_dump($user);
            $authUser = $this->findUserOrCreate($user);
            Auth::login($authUser,true);
            return redirect($this->redirectTo);
        
    }
    public function findUserOrCreate($user){
        $authUser= User::where('email',$user->email)->first();
        if($authUser){
            return $authUser;
        }else{
            return User::create([
                'name' => $user->name,
                'email' => $user->email,
                'rol' => 'client',
                'avatar_social'=> $user->avatar_original,
                'social_id' => $user->id,
            ]); 
        }
        
    }

  
}
