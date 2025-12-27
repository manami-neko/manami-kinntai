<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;



class UserController extends Controller
{
    public function adminLogin()
    {
        return view('admin.login');
    }

    public function staffList()
    {
        $users = User::where('role', 'user')->get();


        return view('admin.stafflist', compact('users'));
    }
}
