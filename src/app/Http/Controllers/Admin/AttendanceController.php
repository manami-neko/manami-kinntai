<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Correction;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function index(Request $request)
    {
       $date = $request->get('date')
        ? Carbon::parse($request->date)
        : Carbon::today();

    $attendances = Attendance::with(['user', 'breakTimes'])
        ->where('day', $date->toDateString())
        ->get();

    return view('admin.list', [
        'date' => $date,
        'attendances' => $attendances,
        'prevDate' => $date->copy()->subDay()->toDateString(),
        'nextDate' => $date->copy()->addDay()->toDateString(),
    ]);
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
