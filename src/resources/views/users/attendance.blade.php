@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-container">

    {{-- =========================
         ★ 出勤前 ＝ レコードなし
       ========================= --}}
    @if (!$attendance)
        <div class="status-label">勤務外</div>

        <div class="date">{{ $today }} ({{ now()->isoFormat('dd') }})</div>
        <div class="time">{{ now()->format('H:i') }}</div>

        <form action="{{ route('attendance.start') }}" method="POST">
    @csrf
    <button class="btn-main">出勤</button>
</form>


    {{-- =========================
         ★ 出勤後 ＝ working
       ========================= --}}
    @elseif ($attendance->status === 'working')
        <div class="status-label">勤務中</div>

        <div class="date">{{ $today }} ({{ now()->isoFormat('dd') }})</div>
        <div class="time">{{ now()->format('H:i') }}</div>

        <div class="btn-row">
            <form action="{{ route('break.start') }}" method="POST">
    @csrf
    <button class="btn-sub">休憩</button>
</form>

            <form action="{{ route('attendance.end') }}" method="POST">
    @csrf
    @method('PATCH')
    <button class="btn-main">退勤</button>
</form>
        </div>


    {{-- =========================
         ★ 休憩中 ＝ resting
       ========================= --}}
    @elseif ($attendance->status === 'resting')
        <div class="status-label">休憩中</div>

        <div class="date">{{ $today }} ({{ now()->isoFormat('dd') }})</div>
        <div class="time">{{ now()->format('H:i') }}</div>

        <form action="{{ route('break.end') }}" method="POST">
    @csrf
    @method('PATCH')
    <button class="btn-main">休憩終了</button>
</form>


    {{-- =========================
         ★ 退勤後 ＝ finished
       ========================= --}}
    @elseif ($attendance->status === 'finished')
        <div class="status-label">勤務終了</div>

        <div class="date">{{ $today }} ({{ now()->isoFormat('dd') }})</div>
        <div class="time">{{ now()->format('H:i') }}</div>

        <p class="text-info">
            お疲れ様でした。
        </p>
    @endif

</div>

@endsection
