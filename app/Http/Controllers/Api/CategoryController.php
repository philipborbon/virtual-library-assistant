<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $categories = Category::withCount('categories')
            ->withCount('books')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }

    public function getCategory($id, Request $request)
    {
        $categories = Category::withCount('categories')
            ->withCount('books')
            ->where('parent_id', $id)
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }
}
