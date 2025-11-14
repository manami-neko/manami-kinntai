<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function create()
    {
        return view('auth.register');
    }
}
