<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UpdateSettingRequest;
use App\Http\Resources\Api\V1\SettingResource;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends BaseController
{
    public function __construct(
        private SettingService $settingService
    ) {}

    public function index(): JsonResponse
    {
        $settings = $this->settingService->getAll();

        return $this->sendResponse(
            SettingResource::collection($settings),
            'تم جلب الإعدادات بنجاح'
        );
    }

    public function update(UpdateSettingRequest $request, string $key): JsonResponse
    {
        $setting = $this->settingService->update($key, $request->value);

        if (!$setting) {
            return $this->sendError('الإعداد غير موجود');
        }

        return $this->sendResponse(
            new SettingResource($setting),
            'تم تحديث الإعداد بنجاح'
        );
    }
    public function destroy(string $key): JsonResponse
{
    $deleted = $this->settingService->delete($key);

    if (is_null($deleted)) {
        return $this->sendError('الإعداد غير موجود');
    }

    return $this->sendResponse(null, 'تم حذف الإعداد بنجاح');
}
}