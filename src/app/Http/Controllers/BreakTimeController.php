<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;

class BreakTimeController extends Controller
{
    public function store(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())
                                ->where('day', now()->format('Y-m-d'))
                                ->first();

        // 勤務状態を休憩へ
        $attendance->update(['status' => 'resting']);

        // 休憩開始を記録
        BreakTime::create([
            'attendance_id' => $attendance->id,
            'start' => now(),
        ]);

        return redirect()->route('attendance.create');
    }

    public function update(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())
                                ->where('day', now()->format('Y-m-d'))
                                ->first();

        $break = BreakTime::where('attendance_id', $attendance->id)
                           ->whereNull('end')
                           ->latest()
                           ->first();

        if ($break) {
            $break->update(['end' => now()]);
        }

        // 状態を勤務中へ
        $attendance->update(['status' => 'working']);

        return redirect()->route('attendance.create');
    }
}
