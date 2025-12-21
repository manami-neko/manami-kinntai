<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech 勤怠管理アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}" />
    @yield('css')

    {{-- ✅ 追加：CSRFトークンをmetaに埋め込む --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="/" class="header__logo">
                <img src="{{ asset('images/CoachTech_White_1.png') }}" alt="coachtech">
            </a>

            @unless(request()->routeIs('login', 'register', 'verification.notice'))


            <div class="header__nav">
                @auth
                    @php
                        // Attendance が渡っている場合のみ判定
                        $isFinished = isset($headerAttendance) && $headerAttendance->status === 'finished';
                    @endphp

                    {{-- 🔽 管理者ヘッダー --}}
                    @if(Auth::user()->role === 'admin')
                        <form class="form" action="/admin/attendance/list" method="get">
                            <button class="header-nav__button">勤怠一覧</button>
                        </form>
                        <form class="form" action="/admin/staff/list" method="get">
                            <button class="header-nav__button">スタッフ一覧</button>
                        </form>
                        <form class="form" action="/admin/stamp_correction_request/list" method="get">
                            <button class="header-nav__button">申請一覧</button>
                        </form>
                    @else

                        {{-- 🔽 一般ユーザーヘッダー --}}
                        @if($isFinished)
                            <form class="form" action="/attendance/list" method="get">
                                <button class="header-nav__button">今月の出勤一覧</button>
                            </form>
                            <form class="form" action="/stamp_correction_request/list" method="get">
                                <button class="header-nav__button">申請一覧</button>
                            </form>
                        @else
                            <form class="form" action="/attendance" method="get">
                                <button class="sell__button">勤怠</button>
                            </form>
                            <form class="form" action="/attendance/list" method="get">
                                <button class="sell__button">勤怠一覧</button>
                            </form>
                            <form class="form" action="/stamp_correction_request/list" method="get">
                                <button class="header-nav__button">申請</button>
                            </form>
                        @endif
                    @endif
                    {{-- 共通：ログアウト --}}
                    <form class="form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="header-nav__button">ログアウト</button>
                    </form>
                @endauth
            </div>
            @endunless
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>