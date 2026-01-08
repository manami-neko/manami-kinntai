<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Correction;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public function show($id)
    {
        $attendance = Attendance::with([
            'user',
            'breakTimes',
            'corrections.breakCorrections'
        ])->findOrFail($id);

        $pending = $attendance->corrections()
            ->where('status', 'pending')
            ->exists();

        return view('admin.show', compact('attendance', 'pending'));
    }

    public function staffAttendanceList($id, Request $request)
    {
        $month = $request->get('month')
            ? Carbon::parse($request->month . '-01')
            : Carbon::now()->startOfMonth();

        $user = User::findOrFail($id);

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $id)
            ->whereBetween('day', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth(),
            ])
            ->get()
            ->keyBy(fn ($a) => Carbon::parse($a->day)->toDateString());

        $dates = collect();
        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates->push($date->copy());
        }


        return view('admin.user', [
            'user'       => $user,
            'month'      => $month,
            'dates'       => $dates,
            'attendances'=> $attendances,
            'prevMonth'  => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth'  => $month->copy()->addMonth()->format('Y-m'),
        ]);
    }




    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'start' => $request->start,
            'end'   => $request->end,
        ]);

        return redirect()
            ->route('admin.attendance.show', $id);
    }

}
