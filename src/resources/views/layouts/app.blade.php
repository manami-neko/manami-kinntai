<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech 勤怠管理アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}" />
    @yield('css')

</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="/" class="header__logo">
                <img src="{{ asset('images/CoachTech_White 1.png') }}" alt="coachtech">
            </a>

            @unless(request()->routeIs('login', 'register', 'verification.notice'))


            <div class="header__nav">
                @auth
                <form class="form" action="/sell" method="get">
                    <button class="sell__button">出品</button>
                </form>
                <form class="form" action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="header-nav__button">ログアウト</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="login-nav__button">ログイン</a>
                @endauth
                <form class="form" action="/mypage" method="get">
                    <button class="header-nav__button">マイページ</button>
                </form>

            </div>
            @endunless
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>