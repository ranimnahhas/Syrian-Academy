<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreEnrollmentRequest;
use App\Http\Resources\Api\V1\EnrollmentRequestResource;
use App\Services\EnrollmentRequestService;
use Illuminate\Http\JsonResponse;

class EnrollmentRequestController extends BaseController
{
    public function __construct(
        private EnrollmentRequestService $enrollmentRequestService
    ) {}

    // طالب يقدم طلب
    public function store(StoreEnrollmentRequest $request): JsonResponse
    {
        $enrollment = $this->enrollmentRequestService->create(
            $request->validated(),
            auth()->id()
        );

        if (!$enrollment) {
            return $this->sendError('لديك طلب تسجيل مسبق لهذا الكورس', [], 409);
        }

        return $this->sendResponse(
            new EnrollmentRequestResource($enrollment),
            'تم تقديم طلب التسجيل بنجاح',
            201
        );
    }

    // أدمن يشوف كل الطلبات
    public function index(): JsonResponse
    {
        $requests = $this->enrollmentRequestService->getAll();

        return $this->sendResponse(
            EnrollmentRequestResource::collection($requests),
            'تم جلب طلبات التسجيل بنجاح'
        );
    }

    // أدمن يشوف تفاصيل طلب
    public function show(int $id): JsonResponse
    {
        $enrollment = $this->enrollmentRequestService->getById($id);

        if (!$enrollment) {
            return $this->sendError('الطلب غير موجود');
        }

        return $this->sendResponse(
            new EnrollmentRequestResource($enrollment),
            'تم جلب تفاصيل الطلب بنجاح'
        );
    }
    public function myRequests(): JsonResponse
{
    $requests = $this->enrollmentRequestService->getByUser(auth()->id());

    return $this->sendResponse(
        EnrollmentRequestResource::collection($requests),
        'تم جلب طلبات التسجيل الخاصة بك بنجاح'
    );
}
}