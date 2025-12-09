<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function create()
    {
    $user = auth()->user();
        $today = now()->format('Y-m-d');

        $attendance = Attendance::where('user_id', $user->id)->where('day', $today)->first();

        return view('users.attendance', [
            'attendance' => $attendance,
            'today' => now()->format('Y年n月j日'),
            'now' => now()->format('H:i'),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');

        Attendance::firstOrCreate(
            ['user_id' => $user->id, 'day' => $today],
            [
                'start' => now(),
                'status' => 'working'
            ]
        );

        return redirect()->route('attendance.create');
    }

    public function update(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())
                                ->where('day', now()->format('Y-m-d'))
                                ->first();

        $attendance->update([
            'end' => now(),
            'status' => 'finished'
        ]);

        return redirect()->route('attendance.create');
    }
}
