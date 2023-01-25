<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Category\CategoryCreateRequest;
use App\Http\Requests\Post\Category\CategoryDeleteRequest;
use App\Http\Requests\Post\Category\CategoryUpdateRequest;
use App\Http\Resources\Post\Category\Category;
use App\Http\Resources\Post\Category\CategoryCollection;
use App\Models\PostCategory;

class CategoriesController extends Controller
{
    public function getCategory(PostCategory $postCategory): Category
    {
        return Category::make($postCategory);
    }

    public function getCategories(): CategoryCollection
    {
        return CategoryCollection::make(PostCategory::all()->all());
    }

    public function addCategories(CategoryCreateRequest $request)
    {

    }

    public function editCategories(CategoryUpdateRequest $request)
    {

    }

    public function deleteCategories(CategoryDeleteRequest $request)
    {

    }
}
