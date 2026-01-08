@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/show.css') }}">
@endsection

@section('content')

<main>
    <div class="attendance-show">

        {{-- タイトル --}}
        <div class="attendance-show__header">
            <h2 class="attendance-show__title">勤怠詳細</h2>
        </div>

        {{-- 詳細カード --}}
        
        <div class="card attendance-show__card">
            <table class="show-table">

                {{-- 名前 --}}
                <tr>
                    <th>名前</th>
                    <td colspan="3">
                        {{ $correction->attendance->user->name }}
                    </td>
                </tr>

                {{-- 日付 --}}
                <tr>
                    <th>日付</th>
                    <td colspan="3">
                        {{ \Carbon\Carbon::parse($correction->attendance->day)->format('Y年') }}
                        &emsp;
                        {{ \Carbon\Carbon::parse($correction->attendance->day)->format('n月j日') }}
                    </td>
                </tr>

                {{-- 出勤・退勤 --}}
                <tr>
                    <th>出勤・退勤</th>
                    <td colspan="3" class="time-cell">
                        {{ \Carbon\Carbon::parse($correction->start)->format('H:i') }}
                        &emsp;〜&emsp;
                        {{ \Carbon\Carbon::parse($correction->end)->format('H:i') }}
                    </td>
                </tr>

                {{-- 休憩 --}}
                @foreach($correction->attendance->breakTimes as $index => $break)
                    <tr>
                        <th>休憩{{ $index + 1 }}</th>
                        <td colspan="3" class="time-cell">
                            {{ \Carbon\Carbon::parse($break->start)->format('H:i') }}
                            〜
                            {{ $break->end
                                ? \Carbon\Carbon::parse($break->end)->format('H:i')
                                : '' }}
                        </td>
                    </tr>
                @endforeach

                @php
                    $newIndex = $correction->attendance->breakTimes->count();
                @endphp

                <tr>
                    <th>休憩{{ $correction->attendance->breakTimes->count() + 1 }}</th>
                    <td colspan="3" class="time-cell empty">
                        {{-- 空欄 --}}
                    </td>

                {{-- 備考 --}}
                <tr>
                    <th>備考</th>
                    <td colspan="3">
                        {{ $correction->note }}
                    </td>
                </tr>

            </table>
        </div>
    </div>
        {{-- 修正ボタン --}}
        @if($correction->status === 'pending')
            <form action="{{ route('admin.approve', $correction->id) }}" method="post" class="attendance-show__button">
                @csrf
                @method('PUT')
                <button class="edit-btn">承認</button>
            </form>
        @endif
</main>


@endsection