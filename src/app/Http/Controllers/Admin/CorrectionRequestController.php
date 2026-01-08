<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Correction;
use Illuminate\Support\Facades\Auth;

class CorrectionRequestController extends Controller
{
    public function userList(Request $request)
        {
            $status = $request->query('status', 'pending'); // 初期は承認待ち

            $corrections = Correction::with('attendance.user')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

            return view('admin.request', compact('corrections', 'status'));
        }

        public function show($id)
    {
        $correction = Correction::with(['attendance.user', 'attendance.breakTimes'])
            ->findOrFail($id);

        return view('admin.show', compact('correction'));
    }

    public function approve($attendance_correct_request_id)
    {
        $correction = Correction::findOrFail($attendance_correct_request_id);
        $attendance = $correction->attendance;

        $attendance->update([
            'start' => $correction->start,
            'end'   => $correction->end,
        ]);

        $attendance->breakTimes()->delete();

        foreach ($correction->breakTimes as $break) {
            $attendance->breakTimes()->create([
                'start' => $break->start,
                'end'   => $break->end,
            ]);
        }

        $correction->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.stamp_correction_request.list')
            ->with('success', '修正申請を承認しました');
    }


}
