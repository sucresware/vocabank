<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_if($request->user != auth()->user(), 403);

            return $next($request);
        })->except(['show']);
    }

    public function show(User $user)
    {
        $samples = $user->samples()->public()->orderBy('created_at', 'DESC')->paginate(12);

        if (request()->ajax()) {
            return $samples;
        }

        return view('user.show', compact('user', 'samples'));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(User $user)
    {
        request()->validate([
            'avatar'      => ['nullable', 'mimes:jpeg,bmp,png,jpg', 'max:2048'],
            'description' => ['nullable', 'max:1024'],
        ]);

        if (request()->hasFile('avatar')) {
            if (!Storage::disk('public')->exists('avatars/')) {
                Storage::disk('public')->makeDirectory('avatars/', 0775, true);
            }

            $avatar_name = $user->id . '_avatar_' . time() . '.' . request()->avatar->getClientOriginalExtension();
            Image::make(request()->avatar)->fit(300, 300)->save(Storage::disk('public')->path('avatars/' . $avatar_name));
            $user->avatar = 'avatars/' . $avatar_name;
        }

        $user->description = request()->description;
        $user->save();

        return redirect(route('users.edit', $user))->with('success', 'Modifications enregistrées !');
    }

    public function editEmail(User $user)
    {
        return view('user.edit_email', compact('user'));
    }

    public function updateEmail(User $user)
    {
        request()->validate([
            'email' => ['required', 'string', 'email', 'not_throw_away', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->email = request()->email;
        $user->save();

        return redirect(route('users.edit.email', $user))->with('success', 'Modifications enregistrées !');
    }

    public function editPassword(User $user)
    {
        return view('user.edit_password', compact('user'));
    }

    public function updatePassword(User $user)
    {
        $validator = Validator::make(request()->input(), [
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($user->password && !Hash::check(request()->password, $user->password)) {
            $validator->errors()->add('password', 'Le mot de passe est incorrect');

            return redirect(route('users.edit.password', $user))->withErrors($validator)->withInput(request()->input());
        }

        $user->password = Hash::make(request()->new_password);
        $user->save();

        return redirect(route('users.edit.password', $user))->with('success', 'Modifications enregistrées !');
    }
}
