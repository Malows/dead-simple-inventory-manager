<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductStockRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(IdRequest $request)
    {
        if ($request->has('ids')) {
            return Product::whereIn('id', $request->input('ids', []))
                ->with('supplier', 'categories')
                ->orderBy('code')
                ->get();
        } else {
            return Product::with('supplier', 'categories')
                ->orderBy('code')
                ->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): Product
    {
        $product = Product::create($request->all());

        $product->categories()->attach($request->get('categories'));

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        $product->load('supplier', 'categories');

        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        $product->fill($request->all())->save();

        $product->categories()->sync($request->get('categories'));

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     *
     * @throws \Exception
     */
    public function destroy(Product $product): Product
    {
        $product->categories()->detach();

        $product->delete();

        return $product;
    }

    /**
     * Update the stock of the specified resource in storage.
     *
     * @throws \Exception
     */
    public function updateStock(ProductStockRequest $request, Product $product): Product
    {
        $product->stock = $product->stock - 1;

        $product->save();

        return $product;
    }
}
