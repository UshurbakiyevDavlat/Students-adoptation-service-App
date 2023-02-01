<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Category\CategoryCreateRequest;
use App\Http\Requests\Post\Category\CategoryUpdateRequest;
use App\Http\Resources\Post\Category\Category;
use App\Http\Resources\Post\Category\CategoryCollection;
use App\Models\PostCategory;
use Illuminate\Http\JsonResponse;

class CategoriesController extends Controller
{
    public function getCategory(PostCategory $postCategory): Category
    {
        return Category::make($postCategory);
    }

    public function getCategories(): CategoryCollection
    {
        return CategoryCollection::make(PostCategory::paginate());
    }

    public function addCategories(CategoryCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = PostCategory::create($data);
        return response()->json(['message' => 'category_success_creation', 'createdCategory' => $category]);
    }

    public function editCategories(PostCategory $category,CategoryUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $updated_category = $category->update($data);
        return response()->json(['message' => 'category_success_update', 'updatedCategory' => $updated_category]);
    }

    public function deleteCategories(PostCategory $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'category_success_deletion', 'categories' => $category]);
    }
}
