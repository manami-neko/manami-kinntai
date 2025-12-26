<?php

namespace App\Http\Controllers;

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
            ->where('user_id', auth()->id())
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

            return view('users.request', compact('corrections', 'status'));
        }

    public function store(Request $request, $id)
        {
            Correction::create([
                'attendance_id' => $id,
                'user_id'       => auth()->id(),
                'start'         => $request->start,
                'end'           => $request->end,
                'note'          => $request->note,
                'status'        => 'pending',
            ]);

            return redirect()
                ->route('users.show', $id)
                ->with('pending', true);
        }

}
