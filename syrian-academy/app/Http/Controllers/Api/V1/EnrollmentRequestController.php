<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreEnrollmentRequest;
use App\Http\Resources\Api\V1\EnrollmentRequestResource;
use App\Services\EnrollmentRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
       public function approve(int $id): JsonResponse
    {
        $result = $this->enrollmentRequestService->approve($id, auth()->id());

        if (!$result) {
            return $this->sendError('لا يمكن تأكيد هذا الطلب', [], 422);
        }

        return $this->sendResponse(
            new EnrollmentRequestResource($result),
            'تم تأكيد الدفع وتوليد الكود بنجاح'
        );
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $result = $this->enrollmentRequestService->reject(
            $id,
            auth()->id(),
            $request->reason ?? null
        );

        if (!$result) {
            return $this->sendError('لا يمكن رفض هذا الطلب', [], 422);
        }

        return $this->sendResponse(
            new EnrollmentRequestResource($result),
            'تم رفض الطلب بنجاح'
        );
    }

    public function regenerateCode(int $id): JsonResponse
    {
        $result = $this->enrollmentRequestService->regenerateCode($id);

        if (!$result) {
            return $this->sendError('لا يمكن إعادة توليد الكود', [], 422);
        }

        return $this->sendResponse(
            new EnrollmentRequestResource($result),
            'تم إعادة توليد الكود بنجاح'
        );
    }

    public function cancelCode(int $id): JsonResponse
    {
        $result = $this->enrollmentRequestService->cancelCode($id);

        if (!$result) {
            return $this->sendError('لا يمكن إلغاء الكود', [], 422);
        }

        return $this->sendResponse(
            new EnrollmentRequestResource($result),
            'تم إلغاء الكود بنجاح'
        );
    }
           // طالب يدخل كود التفعيل
    public function activate(Request $request): JsonResponse
    {
       $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'كود التفعيل مطلوب',
        ]);

       $result = $this->enrollmentRequestService->activate(
            $request->code,
            auth()->id()
        );

        if (!$result) {
            return $this->sendError('الكود غير صحيح أو منتهي الصلاحية', [], 422);
        }

         return $this->sendResponse(
            new EnrollmentRequestResource($result),
            'تم تفعيل الكورس بنجاح'
        ); 
    }
}