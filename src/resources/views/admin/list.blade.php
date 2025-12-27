@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/list.css') }}">
@endsection

@section('content')

<div class="attendance-list">
    <h2 class="attendance-list__title">
            {{ $date->format('Y年m月d日') }}の勤怠
    </h2>

    {{-- 日付切り替え --}}
    <div class="card attendance-list__date">
        <a href="{{ route('admin.attendance.list', ['date' => $prevDate]) }}" class="date-btn">
            ← 前日
        </a>

        <span class="date-text">
            {{ $date->format('Y/m/d') }}
        </span>

        <a href="{{ route('admin.attendance.list', ['date' => $nextDate]) }}" class="date-btn">
            翌日 →
        </a>
    </div>
    <div class="card">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    @php
                        $breakSeconds = $attendance->breakTimes->sum(fn($b) =>
                            $b->start && $b->end
                                ? \Carbon\Carbon::parse($b->end)
                                    ->diffInSeconds(\Carbon\Carbon::parse($b->start))
                                : 0
                        );

                        $workSeconds = \Carbon\Carbon::parse($attendance->end)
                            ->diffInSeconds(\Carbon\Carbon::parse($attendance->start));

                        $totalSeconds = max(0, $workSeconds - $breakSeconds);
                    @endphp

                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->start)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->end)->format('H:i') }}</td>
                    <td>{{ gmdate('H:i', $breakSeconds) }}</td>
                    <td>{{ gmdate('H:i', $totalSeconds) }}</td>
                    <td>
                        <a href="{{ route('admin.attendance.show', $attendance->id) }}">詳細</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection