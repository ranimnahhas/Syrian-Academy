<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\ContactResource;
use App\Http\Resources\Api\V1\EnrollmentRequestResource;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseController
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index(): JsonResponse
    {
        $stats = $this->dashboardService->getStats();

        return $this->sendResponse($stats, 'تم جلب إحصائيات لوحة التحكم بنجاح');
    }

    public function recentRequests(): JsonResponse
    {
        $requests = $this->dashboardService->getRecentRequests();

        return $this->sendResponse(
            EnrollmentRequestResource::collection($requests),
            'تم جلب أحدث الطلبات بنجاح'
        );
    }

    public function recentContacts(): JsonResponse
    {
        $contacts = $this->dashboardService->getRecentContacts();

        return $this->sendResponse(
            ContactResource::collection($contacts),
            'تم جلب أحدث الرسائل بنجاح'
        );
    }
    public function revenueStats(): JsonResponse
    {
        $stats = $this->dashboardService->getRevenueStats();
 
        return $this->sendResponse($stats, 'تم جلب تقرير الإيرادات بنجاح');
    } 
}