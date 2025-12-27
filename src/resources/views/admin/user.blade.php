@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
@endsection

@section('content')

<main class="staff-attendance">

    {{-- タイトル --}}
    <h2 class="staff-attendance__title">
        {{ $user->name }}さんの勤怠
    </h2>

    {{-- 月切り替え --}}
    <div class="card attendance-list__month">
        <a href="{{ route('admin.attendance.user', ['id' => $user->id, 'month' => $prevMonth]) }}" class="month-btn">
            ← 前月
        </a>

        <span class="month-switch__current">
             {{ $month->format('Y/m') }}
        </span>

        <a href="{{ route('admin.attendance.user', ['id' => $user->id, 'month' => $nextMonth]) }}" class="month-btn">
            翌月 →
        </a>
    </div>

    {{-- 勤怠テーブル --}}
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
                @foreach($attendances as $attendance)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($attendance->day)->format('m/d') }}
                            ({{ \Carbon\Carbon::parse($attendance->day)->isoFormat('ddd') }})
                        </td>
                        <td>{{ optional($attendance->start)->format('H:i') }}</td>
                        <td>{{ optional($attendance->end)->format('H:i') }}</td>
                        <td>{{ $attendance->break_time }}</td>
                        <td>{{ $attendance->total_time }}</td>
                        <td>
                            <a href="{{ route('admin.attendance.show', $attendance->id) }}">
                                詳細
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- CSV --}}
    <div class="csv-area">
        <button class="csv-btn">CSV出力</button>
    </div>

</main>

@endsection