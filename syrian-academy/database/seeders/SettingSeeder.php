<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'email', 'value' => 'info@syrianacademy.com', 'type' => 'email', 'group' => 'contact', 'label' => 'البريد الإلكتروني'],
            ['key' => 'phone', 'value' => '+963 11 1234567', 'type' => 'phone', 'group' => 'contact', 'label' => 'رقم الهاتف'],
            ['key' => 'address', 'value' => 'دمشق - سوريا', 'type' => 'text', 'group' => 'contact', 'label' => 'العنوان'],
            ['key' => 'working_hours', 'value' => 'الأحد - الخميس: 9 صباحاً - 5 مساءً', 'type' => 'text', 'group' => 'contact', 'label' => 'أوقات العمل'],
            ['key' => 'site_name', 'value' => 'الأكاديمية السورية', 'type' => 'text', 'group' => 'general', 'label' => 'اسم الموقع'],
            ['key' => 'site_description', 'value' => 'منصة تعليمية سورية', 'type' => 'text', 'group' => 'general', 'label' => 'وصف الموقع'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}