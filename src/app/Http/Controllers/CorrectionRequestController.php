<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Correction;

class CorrectionRequestController extends Controller
{
    public function userList()
        {
            return view('users.request');
        }

}
