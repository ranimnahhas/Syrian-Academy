<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
     public function __construct(
        private UserRepository $userRepository
     ) {}
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
public function getStudentsStats(): array
{
    return $this->userRepository->getStudentsStats();
}

public function getStudents()
{
    return $this->userRepository->getStudents();
}
}