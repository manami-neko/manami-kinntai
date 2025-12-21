<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;


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
        ->where('day', now()->format('Y-m-d'))->first();

        $attendance->update([
            'end' => now(),
            'status' => 'finished'
        ]);

        return redirect()->route('attendance.create');
    }

    public function index(Request $request)
    {
            // 表示月（指定がなければ今月）
        $month = $request->input('month')
            ? Carbon::createFromFormat('Y-m', $request->input('month'))
            : Carbon::now();

        // 月の開始・終了
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth   = $month->copy()->endOfMonth();

        // 勤怠データ（user × 月）
        $attendances = Attendance::with('breakTimes')
            ->where('user_id', auth()->id())
            ->whereBetween('day', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('day'); // ← 日付をキーにする

        // 月の日付一覧
        $dates = CarbonPeriod::create($startOfMonth, $endOfMonth);

        // 前月・翌月
        $prevMonth = $month->copy()->subMonth()->format('Y-m');
        $nextMonth = $month->copy()->addMonth()->format('Y-m');

        return view('users.list', compact('attendances','month','prevMonth','nextMonth','dates'));
    }

    public function show($id)
    {
        $attendance = Attendance::with([
            'user',
            'breakTimes',
            'corrections.breakCorrections'
        ])->where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

        return view('users.show', compact('attendance'));
    }
}

