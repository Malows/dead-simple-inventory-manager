<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Product::with('supplier', 'categories')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductRequest  $request
     *
     * @return Product
     */
    public function store(ProductRequest $request): Product
    {
        $product = Product::create($request->all());

        $product->categories()->attach($request->get('categories'));

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     *
     * @return Product
     */
    public function show(Product $product): Product
    {
        $product->load('supplier', 'categories');

        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductRequest  $request
     * @param  Product  $product
     *
     * @return Product
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
     * @param Product $product
     *
     * @return Product
     *
     * @throws \Exception
     */
    public function destroy(Product $product): Product
    {
        $product->categories()->detach();

        $product->delete();

        return $product;
    }
}
