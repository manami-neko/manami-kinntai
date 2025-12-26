@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users/request.css') }}">
@endsection

@section('content')

<main class="request-list">

    <h2>申請一覧</h2>

    <div class="request-tabs">
        <a href="{{ route('users.request', ['status' => 'pending']) }}"
        class="tab {{ $status === 'pending' ? 'active' : '' }}">
            承認待ち
        </a>

        <a href="{{ route('users.request', ['status' => 'approved']) }}"
        class="tab {{ $status === 'approved' ? 'active' : '' }}">
            承認済み
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>

        <tbody>
            @foreach($corrections as $correction)
                <tr>
                    <td>
                        @if($correction->status === 'pending')
                            承認待ち
                        @elseif($correction->status === 'approved')
                            承認済み
                        @else
                            却下
                        @endif
                    </td>
                    <td>{{ $correction->attendance->user->name }}</td>
                    <td>{{ $correction->attendance->day }}</td>
                    <td>{{ $correction->note }}</td>
                    <td>{{ $correction->created_at->format('Y/m/d') }}</td>
                    <td>
                        <a href="{{ route('users.show', $correction->attendance_id) }}">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</main>

@endsection