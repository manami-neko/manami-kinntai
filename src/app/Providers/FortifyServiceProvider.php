<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });

        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = $request->user();
                if (!$user) return redirect('/login');

                // セッションの role を優先
                $role = session('role', $user->role);

                return redirect($role === 'admin'
                    ? RouteServiceProvider::ADMIN_HOME
                    : RouteServiceProvider::HOME);
            }
        });


        // ログアウト後のリダイレクト設定
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                $previousUrl = url()->previous(); // 直前のURLを取得

                // 管理者ログインページから来たなら admin用に戻す
                if (str_contains($previousUrl, '/admin')) {
                    return redirect('/admin/login');
                }

                // それ以外は一般ログインページへ
                return redirect('/login');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(
            function () {
                return view('auth.register');
            }
        );

        Fortify::loginView(function () {
            return view('auth.login');
        });


        RateLimiter::for(
            'login',
            function (Request $request) {
                $email = (string) $request->email;

                return Limit::perMinute(10)->by($email . $request->ip());
            }
        );

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                // 管理者用 role で認証
                if ($request->role === 'admin' && $user->role === 'admin') {
                    // ログイン時にセッションに role を保存
                    session(['role' => 'admin']);
                    return $user;
                }

                if ($request->role !== 'admin' && $user->role === 'user') {
                    session(['role' => 'user']);
                    return $user;
                }
            }
            return null;
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
        
    }
}
