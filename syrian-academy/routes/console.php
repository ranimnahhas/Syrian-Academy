<?php

use Illuminate\Support\Facades\Schedule;

// تنظيف الأكواد المنتهية يومياً الساعة 12:00 صباحاً
Schedule::call(function () {
    \App\Models\EnrollmentRequest::where('status', 'paid')
        ->where('code_expires_at', '<', now())
        ->update(['status' => 'expired']);
})->dailyAt('00:00');

// حذف التوكنات القديمة أسبوعياً
Schedule::call(function () {
    \DB::table('personal_access_tokens')
        ->where('created_at', '<', now()->subDays(7))
        ->delete();
})->weeklyOn(1, '03:00'); // كل اثنين الساعة 3 صباحاً

// تنظيف الإشعارات المقروءة القديمة شهرياً
Schedule::call(function () {
    \App\Models\Notification::where('is_read', true)
        ->where('read_at', '<', now()->subMonth())
        ->delete();
})->monthlyOn(1, '04:00'); // أول يوم من كل شهر الساعة 4 صباحاً