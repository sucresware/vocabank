<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginWithFourSucres()
    {
        return \Socialite::with('foursucres')->redirect();
    }

    public function loginWithFourSucresCallback()
    {
        try {
            $user = \Socialite::with('foursucres')->user();
            dd($user);
        } catch (\Exception $e) {
            dump($e->getMessage());

            // return redirect(route('login'));
        }
    }
}
