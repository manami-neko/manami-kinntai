<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Correction;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function index()
    {
        return view('admin.list');
    }

    public function approve(Correction $correction)
    {
        DB::transaction(function () use ($correction) {

            // ① attendance を更新
            $attendance = $correction->attendance;

            $attendance->update([
                'start' => $correction->start,
                'end'   => $correction->end,
            ]);

            // ② corrections を承認済みに
            $correction->update([
                'status' => 'approved',
            ]);
        });

        return redirect()
            ->back();
    }
}
