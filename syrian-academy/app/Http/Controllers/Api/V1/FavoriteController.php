<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ToggleFavoriteRequest;
use App\Http\Requests\Api\V1\UpdateFavoriteNoteRequest;
use App\Http\Requests\Api\V1\UpdateFavoritePositionRequest;
use App\Http\Resources\Api\V1\FavoriteResource;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;

class FavoriteController extends BaseController
{
    public function __construct(
        private FavoriteService $favoriteService
    ) {}

    public function index(): JsonResponse
    {
        $favorites = $this->favoriteService->getUserFavorites(auth()->id());

        return $this->sendResponse(
            FavoriteResource::collection($favorites),
            'تم جلب المفضلة بنجاح'
        );
    }

    public function toggle(ToggleFavoriteRequest $request): JsonResponse
    {
        $result = $this->favoriteService->toggle(auth()->id(), $request->course_id);

        return $this->sendResponse(
            ['is_favorited' => $result['is_favorited']],
            $result['message']
        );
    }

    public function updateNote(UpdateFavoriteNoteRequest $request): JsonResponse
    {
        $favorite = $this->favoriteService->updateNote(
            auth()->id(),
            $request->course_id,
            $request->note
        );

        if (!$favorite) {
            return $this->sendError('الكورس غير موجود في المفضلة');
        }

        return $this->sendResponse(
            new FavoriteResource($favorite),
            'تم تحديث الملاحظة بنجاح'
        );
    }

    public function updatePosition(UpdateFavoritePositionRequest $request): JsonResponse
    {
        $favorite = $this->favoriteService->updatePosition(
            auth()->id(),
            $request->course_id,
            $request->position
        );

        if (!$favorite) {
            return $this->sendError('الكورس غير موجود في المفضلة');
        }

        return $this->sendResponse(
            new FavoriteResource($favorite),
            'تم تحديث الترتيب بنجاح'
        );
    }
}