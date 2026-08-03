<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $authService
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
}