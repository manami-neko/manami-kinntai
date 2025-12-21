@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users/list.css') }}">
@endsection

@section('content')

<div class="attendance-list">
    <h2 class="attendance-list__title">勤怠一覧</h2>

    {{-- 月切り替え --}}
    <div class="card attendance-list__month">
        <a href="{{ url('/attendance/list?month='.$prevMonth) }}" class="month-btn">
            ← 前月
        </a>

        <span class="month-text">
            {{ $month->format('Y/m') }}
        </span>

        <a href="{{ url('/attendance/list?month='.$nextMonth) }}" class="month-btn">
            翌月 →
        </a>
    </div>

    <div class="card">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dates as $date)
                    @php
                        $attendance = $attendances[$date->toDateString()] ?? null;

                        // 出勤・退勤時刻
                        $startTime = $attendance ? \Carbon\Carbon::parse($attendance->start)->format('H:i') : '';
                        $endTime   = $attendance ? \Carbon\Carbon::parse($attendance->end)->format('H:i') : '';

                        if ($attendance) {
                            // 休憩時間合計（秒→H:i）
                            $breakSeconds = $attendance->breakTimes->sum(function ($break) {
                                return $break->start && $break->end
                                    ? \Carbon\Carbon::parse($break->end)->diffInSeconds(\Carbon\Carbon::parse($break->start))
                                    : 0;
                            });
                            $breakTime = gmdate('H:i', $breakSeconds);

                            // 勤務時間合計（休憩を引いた時間）
                            $workSeconds = $attendance->start && $attendance->end
                                ? \Carbon\Carbon::parse($attendance->end)->diffInSeconds(\Carbon\Carbon::parse($attendance->start))
                                : 0;
                            $totalTime = gmdate('H:i', max(0, $workSeconds - $breakSeconds));
                        } else {
                            $breakTime = '';
                            $totalTime = '';
                        }
                    @endphp

                    <tr>
                        <td>{{ $date->isoFormat('MM/DD(ddd)') }}</td>
                        <td>{{ $startTime }}</td>
                        <td>{{ $endTime }}</td>
                        <td>{{ $breakTime }}</td>
                        <td>{{ $totalTime }}</td>
                        <td>
                            @if($attendance)
                                <a href="{{ route('users.show', $attendance->id) }}">詳細</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
