@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users/list.css') }}">
@endsection

@section('content')

<div class="attendance-list">
    <h2 class="attendance-list__title">勤怠一覧</h2>

    {{-- 月切り替え --}}
    <div class="attendance-list__month">
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

    {{-- テーブル --}}
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
                @endphp

            <tr>
                <td>
                     {{ $date->isoFormat('MM/DD(ddd)') }}
                </td>
                <td>
                    {{ $attendance && $attendance->start
                        ? \Carbon\Carbon::parse($attendance->start)->format('H:i')
                        : '' }}
                </td>
                <td>
                    {{ $attendance && $attendance->end
                    ? \Carbon\Carbon::parse($attendance->end)->format('H:i')
                    : '' }}
                </td>

                <td>
                    @if($attendance)
                    {{ gmdate(
                        'H:i',
                        $attendance->breakTimes->sum(function ($break) {
                            if ($break->start && $break->end) {
                                return \Carbon\Carbon::parse($break->end)
                                    ->diffInSeconds(\Carbon\Carbon::parse($break->start));
                            }
                            return 0;
                        })
                    ) }}
                @endif
                </td>

                <td>
                                    @if($attendance && $attendance->start && $attendance->end)
                    @php
                        $workSeconds = \Carbon\Carbon::parse($attendance->end)
                            ->diffInSeconds(\Carbon\Carbon::parse($attendance->start));

                        $breakSeconds = $attendance->breakTimes->sum(function ($break) {
                            if ($break->start && $break->end) {
                                return \Carbon\Carbon::parse($break->end)
                                    ->diffInSeconds(\Carbon\Carbon::parse($break->start));
                            }
                            return 0;
                        });
                    @endphp

                    {{ gmdate('H:i', max(0, $workSeconds - $breakSeconds)) }}
                @endif

                </td>

                <td>
                    @if($attendance)
                    <a href="{{ url('/attendance/detail/'.$attendance->id) }}">詳細</a>
                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection