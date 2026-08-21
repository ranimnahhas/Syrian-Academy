<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreContactRequest;
use App\Http\Resources\Api\V1\ContactResource;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends BaseController
{
    public function __construct(
        private ContactService $contactService
    ) {}

    // إرسال رسالة (عام)
    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->create($request->validated());

        return $this->sendResponse(
            new ContactResource($contact),
            'تم إرسال رسالتك بنجاح',
            201
        );
    }

    // عرض كل الرسائل (أدمن)
    public function index(): JsonResponse
    {
        $contacts = $this->contactService->getAll();

        return $this->sendResponse(
            ContactResource::collection($contacts),
            'تم جلب الرسائل بنجاح'
        );
    }

    // عرض الرسائل غير المقروءة (أدمن)
    public function unread(): JsonResponse
    {
        $contacts = $this->contactService->getUnread();

        return $this->sendResponse(
            ContactResource::collection($contacts),
            'تم جلب الرسائل غير المقروءة بنجاح'
        );
    }

    // عرض رسالة (أدمن)
    public function show(int $id): JsonResponse
    {
        $contact = $this->contactService->getById($id);

        if (!$contact) {
            return $this->sendError('الرسالة غير موجودة');
        }

        return $this->sendResponse(
            new ContactResource($contact),
            'تم جلب الرسالة بنجاح'
        );
    }

    // تحديد كمقروءة (أدمن)
    public function markAsRead(int $id): JsonResponse
    {
        $contact = $this->contactService->markAsRead($id);

        if (!$contact) {
            return $this->sendError('الرسالة غير موجودة');
        }

        return $this->sendResponse(
            new ContactResource($contact),
            'تم تحديد الرسالة كمقروءة'
        );
    }

    // حذف رسالة (أدمن)
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->contactService->delete($id);

        if (!$deleted) {
            return $this->sendError('الرسالة غير موجودة');
        }

        return $this->sendResponse(null, 'تم حذف الرسالة بنجاح');
    }
}