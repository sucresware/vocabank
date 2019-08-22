<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        return User::orderBy('created_at', 'ASC')->paginate(30);
    }

    public function show(User $user){
        return $user;
    }
}
