<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // تخصيص رابط استعادة كلمة المرور للـ API
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return config('app.url') . '/api/v1/reset-password?token=' . $token . '&email=' . $user->email;
        });

        // تشغيل الـ Broadcasting
        Broadcast::routes();

        // Rate Limiting للمصادقة
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Rate Limiting عام
        RateLimiter::for('public', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Rate Limiting محمي
        RateLimiter::for('protected', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}