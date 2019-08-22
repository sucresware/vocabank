<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerifyUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        $verify_user = VerifyUser::where('token', $token)->firstOrFail();

        return view('auth.passwords.reset', compact('token'));
    }

    public function reset($token)
    {
        $verify_user = VerifyUser::where('token', $token)->firstOrFail();
        $user = $verify_user->user;

        request()->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->password = Hash::make(request()->new_password);
        $user->save();

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Mot de passe modifié, bon retour !');
    }
}
