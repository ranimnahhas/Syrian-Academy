<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function getAll()
    {
        return $this->categoryRepository->all();
    }

    public function getActive()
    {
        return $this->categoryRepository->getActive();
    }

    public function getById(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function create(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('categories', 'public');
        }

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        return $this->categoryRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return null;
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $data['image']->store('categories', 'public');
        }

        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return $category;
    }

    public function delete(int $id): ?bool
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return null;
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return true;
    }
}