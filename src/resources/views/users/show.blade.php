@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users/show.css') }}">
@endsection

@section('content')

<main>
    <div class="attendance-show">
        {{-- タイトル --}}
        <div class="attendance-show__header">
            <h2 class="attendance-show__title">勤怠詳細</h2>
        </div>

        {{-- 詳細カード --}}
        <form action="{{ route('users.show', $attendance->id) }}" method="post" class="card attendance-show__card">
            @csrf
        
            <table class="show-table">

                {{-- 名前 --}}
                <tr>
                    <th>名前</th>
                    <td colspan="3">
                        {{ $attendance->user->name }}
                    </td>
                </tr>

                {{-- 日付 --}}
                <tr>
                    <th>日付</th>
                    <td colspan="3">
                        {{ \Carbon\Carbon::parse($attendance->day)->format('Y年n月j日') }}
                    </td>
                </tr>

                {{-- 出勤・退勤 --}}
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="text" name="start" value="{{ old('start', \Carbon\Carbon::parse($attendance->start)->format('H:i')) }}">
                    </td>
                    <td>〜</td>
                    <td>
                        <input type="text" name="end" value="{{ old('end', optional($attendance->end) ? \Carbon\Carbon::parse($attendance->end)->format('H:i') : '') }}">
                    </td>
                </tr>

                {{-- 休憩 --}}
                @foreach($attendance->breakTimes as $index => $break)
                    <tr>
                        <th>休憩{{ $index + 1 }}</th>
                        <td>
                            <input type="text" name="breaks[{{ $index }}][start]" value="{{ old("breaks.$index.start", \Carbon\Carbon::parse($break->start)->format('H:i')) }}">
                        </td>
                        <td>〜</td>
                        <td>
                            <input type="text" name="breaks[{{ $index }}][end]" value="{{ old("breaks.$index.end", optional($break->end) ? \Carbon\Carbon::parse($break->end)->format('H:i') : '') }}">
                        </td>
                    </tr>
                @endforeach

                @php
                    $newIndex = $attendance->breakTimes->count();
                @endphp

                <tr>
                    <th>休憩{{ $newIndex + 1 }}</th>
                    <td>
                        <input type="text"
                            name="breaks[{{ $newIndex }}][start]"
                            value="{{ old("breaks.$newIndex.start") }}">
                    </td>
                    <td>〜</td>
                    <td>
                        <input type="text"
                            name="breaks[{{ $newIndex }}][end]"
                            value="{{ old("breaks.$newIndex.end") }}">
                    </td>
                </tr>

                {{-- 備考 --}}
                <tr>
                    <th>備考</th>
                    <td colspan="3">
                        <input type="text" name="note"
                        value="{{ old('note', optional($attendance->corrections->last())->note) }}">
                    </td>
                </tr>

            </table>
        </form>

        {{-- 修正ボタン --}}
        @if(!$pending)
        <div class="attendance-show__button">
            <button class="edit-btn">修正</button>
        </div>
        @endif


        @if($pending)
            <p class="pending-message">
                *承認待ちのため修正できません。
            </p>
        @endif

    </div>
</main>

@endsection
