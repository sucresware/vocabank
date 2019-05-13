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
        $samples = $user->samples()->orderBy('created_at', 'DESC')->paginate(10);

        return view('user.show', compact('user', 'samples'));
    }

    // public function edit($id)
    // { }

    // public function update(Request $request, $id)
    // { }

    // public function destroy($id)
    // { }
}
