<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
   public function register(array $data): array
{
    $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'] ?? null,
        'password' => Hash::make($data['password']),
        'role'     => 'student', 
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    return [
        'user'  => $user,
        'token' => $token,
    ];
}

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات الدخول غير صحيحة'],
            ]);
        }

        // تحديث وقت آخر دخول
        $user->update(['last_login_at' => now()]);

        // حذف التوكنات القديمة وإنشاء جديد
        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}