<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Http\Resources\Admin\Product\ProductCollection;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $maxPrice = $request->maxPrice;
        $minPrice = $request->minPrice;
        $categoryId = $request->category_id;
        $key = $request->key;
        $sortKey = $request->sort;

        $products = Product::byCategory($categoryId)
            ->priceRange($minPrice, $maxPrice)
            ->search($key)
            ->sort($sortKey)
            ->paginate(3);

        return new ProductCollection($products);
    }
    public function show(Product $Product)
    {
        return new ProductResource($Product);
    }

    public function store(StoreProductRequest  $request)
    {
        $validate = $request->validated();
        $image = $request->file('image')->store('', ['disk' => 'image']);
        $product = Product::create(array_merge($request->only([
            'name', 'description', 'image', 'price','category_id'
        ]), ['image' => $image]));

        $attribute = new ProductAttribute;
        $attribute->attribute_name = $request->input('attribute_name');
        $attribute->attribute_value = $request->input('attribute_value');

        $product->attributes()->save($attribute);
        $product->load('attributes');
        return new ProductResource($product);
    }
    public function update(UpdateProductRequest $request, Product $product)
    {
    $validate = $request->validated();
    if($request->hasFile('image')) {
        $image = $request->file('image')->store('', ['disk' => 'image']);
        $product->update(array_merge($request->only([
            'name', 'description', 'price', 'category_id'
        ]), ['image' => $image]));
    } else {
        $product->update($request->only([
            'name', 'description', 'price', 'category_id'
        ]));
    }
    if($request->has('attribute_name') && $request->has('attribute_value')) {
        $attribute = $product->attributes()->first();
        $attribute->attribute_name = $request->input('attribute_name');
        $attribute->attribute_value = $request->input('attribute_value');
        $attribute->save();
    }

    $product->load('attributes');

    return new ProductResource($product);
    }
    public function destroy(Request $request, Product $Product)
    {
        $Product->delete();
        return response()->noContent();
    }
}
