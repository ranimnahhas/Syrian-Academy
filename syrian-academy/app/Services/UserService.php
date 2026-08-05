<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool|string
    {
        if (!\Hash::check($currentPassword, $user->password)) {
            return 'كلمة المرور الحالية غير صحيحة';
        }

        $user->update([
            'password' => \Hash::make($newPassword),
        ]);

        return true;
    }
    public function deleteAccount(User $user): void
{
    // حذف كل التوكنات
    $user->tokens()->delete();
    
    // Soft delete
    $user->delete();
}
}