<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
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
            'name', 'description', 'image', 'price'
        ]), ['image' => $image]));
        $product->attributes()->sync($request->attribute_ids);

        return new ProductResource($product);
    }
    public function update(UpdateProductRequest $request, Product $product)
    {

        $data = $request->validated();
            if ($request->hasFile('thumbnail')) {
                $image =
                    $request->file('image')->store('', ['disk' => 'image']);
                $data['image'] = $image;
            }
            $product->update(array_merge($request->only([
                'name', 'description', 'image', 'price','category_id'
            ]), ['image' => $image]));

            $product->attributes()->sync($request->attribute_id);

        return new ProductResource($product);
    }
    public function destroy(Request $request, Product $Product)
    {
        $Product->delete();
        return response()->noContent();
    }
}
