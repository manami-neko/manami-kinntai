@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/stafflist.css') }}">
@endsection

@section('content')

<main class="staff-list">

    <h2 class="staff-list__title">スタッフ一覧</h2>

    <table class="staff-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('admin.attendance.user', $user->id) }}">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</main>

@endsection