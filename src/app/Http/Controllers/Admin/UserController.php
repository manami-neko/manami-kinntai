<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;


class UserController extends Controller
{
    public function adminLogin()
    {
        return view('admin.login');
    }
}
