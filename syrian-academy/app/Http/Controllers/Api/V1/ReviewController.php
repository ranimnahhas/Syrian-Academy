<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreReviewRequest;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends BaseController
{
    public function __construct(
        private ReviewService $reviewService
    ) {}

    // عرض تقييمات كورس (للجميع)
    public function courseReviews(int $courseId): JsonResponse
    {
        $reviews = $this->reviewService->getByCourse($courseId);

        return $this->sendResponse(
            ReviewResource::collection($reviews),
            'تم جلب التقييمات بنجاح'
        );
    }

    // إضافة تقييم (للطالب المسجل)
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $result = $this->reviewService->create($request->validated(), auth()->id());

        if (isset($result['error'])) {
            return $this->sendError($result['error'], [], 422);
        }

        return $this->sendResponse(
            new ReviewResource($result),
            'تم إضافة التقييم بنجاح - بانتظار الموافقة',
            201
        );
    }

    // حذف تقييم (لصاحب التقييم أو الأدمن)
    public function destroy(int $id): JsonResponse
    {
        $review = $this->reviewService->getById($id);

        if (!$review) {
            return $this->sendError('التقييم غير موجود');
        }

        if ($review->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return $this->sendError('غير مصرح', [], 403);
        }

        $this->reviewService->delete($id);

        return $this->sendResponse(null, 'تم حذف التقييم بنجاح');
    }

    // أدمن: عرض التقييمات المعلقة
    public function pending(): JsonResponse
    {
        $reviews = $this->reviewService->getPending();

        return $this->sendResponse(
            ReviewResource::collection($reviews),
            'تم جلب التقييمات المعلقة بنجاح'
        );
    }

    // أدمن: الموافقة على تقييم
    public function approve(int $id): JsonResponse
    {
        $review = $this->reviewService->approve($id);

        if (!$review) {
            return $this->sendError('التقييم غير موجود');
        }

        return $this->sendResponse(
            new ReviewResource($review),
            'تمت الموافقة على التقييم بنجاح'
        );
    }
}