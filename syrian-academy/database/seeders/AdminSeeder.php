<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@syrianacademy.com'],
            [
                'name'     => 'مدير النظام',
                'phone'    => '0999000000',
                'password' => Hash::make('Admin@123'),
                'role'     => 'admin',
            ]
        );
    }
}