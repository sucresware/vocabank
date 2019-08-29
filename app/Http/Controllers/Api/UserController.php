<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::orderBy('created_at', 'ASC')->paginate(30);
    }

    public function show(User $user)
    {
        return $user;
    }
}
