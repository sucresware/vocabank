<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'ASC')->paginate(20);

        return view('user.index', compact('users'));
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
            'description' => ['nullable', 'max:1024'],
        ]);

        $user->description = request()->description;
        $user->save();

        return redirect(route('users.show', $user));
    }

    // public function destroy($id)
    // { }
}
