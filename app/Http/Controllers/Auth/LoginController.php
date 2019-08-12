<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

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
        return Socialite::with('foursucres')->redirect();
    }

    public function loginWithFourSucresCallback()
    {
        try {
            $socialite_user = Socialite::with('foursucres')->user();

            $user = User::where('fourSucres_account->id', $socialite_user->getId())->first();

            if (!$user && $socialite_user->getEmail()) {
                $user = User::where('email', $socialite_user->getEmail())->first();
            }

            if (!$user) {
                $user = User::create([
                    'name'               => $socialite_user->getName(),
                    'email'              => $socialite_user->getEmail(),
                    'fourSucres_account' => (array) $socialite_user,
                ]);

                $fourSucres_avatar_url = $socialite_user->getAvatar();
                $avatar_name = $user->id . '_avatar_' . time() . '.' . pathinfo(parse_url($fourSucres_avatar_url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $avatar_path = storage_path('app/public/avatars/' . $avatar_name);

                file_put_contents($avatar_path, file_get_contents($fourSucres_avatar_url));
                Image::make($avatar_path)->fit(300, 300)->save();
                $user->avatar = 'avatars/' . $avatar_name;

                $user->save();
            }

            Auth::login($user);

            return redirect('home');
        } catch (\Exception $e) {
            return redirect(route('login'))->with('error', 'Une erreur est survenue. Veuillez réessayer. ' . $e->getMessage());
        }
    }
}
