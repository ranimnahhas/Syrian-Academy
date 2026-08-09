<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast; // 👈 أضف هذا السطر

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

        // 👇 أضف هذا السطر لتشغيل الـ Broadcasting
        Broadcast::routes();
    }
}