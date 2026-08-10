<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Api\V1\ChangePasswordRequest;

class AuthController extends BaseController
{
    public function __construct(
          private AuthService $authService,
          private UserService $userService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return $this->sendResponse([
            'user'  => new UserResource($result['user']),
            'token' => $result['token'],
        ], 'تم التسجيل بنجاح', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return $this->sendResponse([
            'user'  => new UserResource($result['user']),
            'token' => $result['token'],
        ], 'تم تسجيل الدخول بنجاح');
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout(auth()->user());

        return $this->sendResponse(null, 'تم تسجيل الخروج بنجاح');
    }

    public function user(): JsonResponse
    {
        return $this->sendResponse(
            new UserResource(auth()->user()),
            'بيانات المستخدم'
        );
    }
    // تحديث الملف الشخصي
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
      $user = $this->userService->updateProfile(auth()->user(), $request->validated());

      return $this->sendResponse(
            new UserResource($user),
            'تم تحديث الملف الشخصي بنجاح'
       );
    }   

    // تغيير كلمة المرور
  

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
       $result = $this->userService->changePassword(
           auth()->user(),
           $request->current_password,
           $request->new_password
       );

        if ($result !== true) {
           return $this->sendError($result, [], 422);
           }

       return $this->sendResponse(null, 'تم تغيير كلمة المرور بنجاح');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
       $status = $this->authService->forgotPassword($request->email);

       if ($status !== Password::RESET_LINK_SENT) {
           return $this->sendError('فشل في إرسال رابط الاستعادة', [], 500);
        }

        return $this->sendResponse(null, 'تم إرسال رابط استعادة كلمة المرور إلى إيميلك');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword($request->validated());
 
         if ($status !== Password::PASSWORD_RESET) {
         return $this->sendError('رمز التحقق غير صحيح أو منتهي الصلاحية', [], 422);
        }

       return $this->sendResponse(null, 'تم تغيير كلمة المرور بنجاح');
    }
    public function deleteAccount(): JsonResponse
{
    $this->userService->deleteAccount(auth()->user());

    return $this->sendResponse(null, 'تم حذف الحساب بنجاح');
}
public function studentsReport(): JsonResponse
{
    $stats = $this->userService->getStudentsStats();

    return $this->sendResponse($stats, 'تم جلب تقرير الطلاب بنجاح');
}

public function studentsList(): JsonResponse
{
    $students = $this->userService->getStudents();

    return $this->sendResponse(
        \App\Http\Resources\Api\V1\UserResource::collection($students),
        'تم جلب قائمة الطلاب بنجاح'
    );
}
  
}