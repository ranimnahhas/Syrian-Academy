<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Api\V1\CourseResource;

class CategoryController extends BaseController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();

        return $this->sendResponse(
            CategoryResource::collection($categories),
            'تم جلب التصنيفات بنجاح'
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return $this->sendResponse(
            new CategoryResource($category),
            'تم إنشاء التصنيف بنجاح',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);

        if (!$category) {
            return $this->sendError('التصنيف غير موجود');
        }

        return $this->sendResponse(
            new CategoryResource($category),
            'تم جلب التصنيف بنجاح'
        );
    }

   public function update(UpdateCategoryRequest $request, int $id): JsonResponse
{
    $category = $this->categoryService->update($id, $request->validated());

    if (!$category) {
        return $this->sendError('التصنيف غير موجود');
    }

    return $this->sendResponse(
        new CategoryResource($category),
        'تم تحديث التصنيف بنجاح'
    );
}

    public function destroy(int $id): JsonResponse
{
    $deleted = $this->categoryService->delete($id);

    if (is_null($deleted)) {
        return $this->sendError('التصنيف غير موجود');
    }

    return $this->sendResponse(null, 'تم حذف التصنيف بنجاح');
}

public function courses(int $id): JsonResponse
{
    $category = $this->categoryService->getById($id);

    if (!$category) {
        return $this->sendError('التصنيف غير موجود');
    }

    return $this->sendResponse(
        CourseResource::collection($category->courses),
        'تم جلب كورسات التصنيف بنجاح'
    );
}
}