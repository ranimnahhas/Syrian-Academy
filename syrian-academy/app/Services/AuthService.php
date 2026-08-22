<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        $user->update(['last_login_at' => now()]);
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

    public function forgotPassword(string $email): string
    {
        // إرسال رابط إعادة تعيين كلمة المرور عبر البريد الإلكتروني
        return Password::sendResetLink(
            ['email' => $email],
            function ($user, $token) {
                // تخصيص البريد الإلكتروني
                $this->sendResetEmail($user, $token);
            }
        );
    }

    /**
     * إرسال بريد إعادة تعيين كلمة المرور المخصص
     */
    protected function sendResetEmail($user, $token): void
    {
        // هنا يمكنك إرسال بريد إلكتروني مخصص
        // يمكنك استخدام Mailable أو Mail facade
        Mail::send('emails.reset-password', [
            'user' => $user,
            'token' => $token,
            'email' => $user->email
        ], function ($message) use ($user) {
            $message->to($user->email)
                   ->subject('إعادة تعيين كلمة المرور');
        });
    }

    public function resetPassword(array $data): string
    {
        return Password::reset(
            $data,
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
    }
}