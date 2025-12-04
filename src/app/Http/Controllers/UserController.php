<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades;


class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        return view('auth.login');
    }

    public function create()
    {
        return view('auth.register');
    }
}
