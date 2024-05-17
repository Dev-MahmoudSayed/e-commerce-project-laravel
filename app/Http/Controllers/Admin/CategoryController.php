<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Http\Resources\Admin\Category\CategoryCollection;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return new CategoryCollection(Category::all());
    }
    public function show(Category $Category)
    {
        return new CategoryResource($Category);
    }
    public function store(StoreCategoryRequest  $request)
    {
        $validate = $request->validated();
        $Category = Category::create($validate);
        return new CategoryResource($Category); //message
    }
    public function update(StoreCategoryRequest $request, Category $Category)
    {
        $validate = $request->validated();
        $Category->update($validate);
        return new CategoryResource($Category);
    }
    public function destroy(Request $request, Category $Category)
    {
        $Category->delete();
        return response()->noContent();
    }
}
