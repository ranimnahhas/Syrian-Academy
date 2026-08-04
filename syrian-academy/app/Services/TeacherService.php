<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TeacherRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository
    ) {}

    public function getAll()
    {
        return $this->teacherRepository->all();
    }

    public function getById(int $id)
    {
        return $this->teacherRepository->find($id, ['user', 'courses']);
    }

    public function create(array $data)
    {
        // دايماً إنشاء مستخدم جديد برول teacher
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'teacher',
        ]);
        $data['user_id'] = $user->id;

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo'] = $data['photo']->store('teachers', 'public');
        }

        unset($data['name'], $data['email'], $data['phone'], $data['password']);

        return $this->teacherRepository->create($data);
    }

    public function update(int $id, array $data)
{
    $teacher = $this->teacherRepository->find($id);

    if (!$teacher) {
        return null;
    }

    // تحديث بيانات المستخدم
    if ($teacher->user) {
        $userData = [];
        if (isset($data['name'])) $userData['name'] = $data['name'];
        if (isset($data['email'])) $userData['email'] = $data['email'];
        if (isset($data['phone'])) $userData['phone'] = $data['phone'];
        if (isset($data['password'])) $userData['password'] = Hash::make($data['password']);
        
        if (!empty($userData)) {
            $teacher->user->update($userData);
        }
    }

    // تحديث صورة المدرس
    if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }
        $data['photo'] = $data['photo']->store('teachers', 'public');
    }

    unset($data['name'], $data['email'], $data['phone'], $data['password']);

    if (!empty($data)) {
        $teacher->update($data);
    }

    return $teacher->fresh(['user', 'courses']);
}

    public function delete(int $id): ?bool
    {
        $teacher = $this->teacherRepository->find($id);

        if (!$teacher) {
            return null;
        }

        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        // حذف المستخدم مع المدرس
        if ($teacher->user) {
            $teacher->user->delete();
        }

        $teacher->delete();
        return true;
    }
}