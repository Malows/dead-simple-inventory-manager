<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductStockRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $user = $request->user('api');

        return $user->products()->with('supplier', 'categories', 'storageLocation')
                ->orderBy('code')
                ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): Product
    {
        $user = $request->user('api');

        $product = new Product($request->validated());

        $user->products()->save($product);

        $product->categories()->attach($request->get('categories'));

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        $product->load('supplier', 'categories', 'storageLocation');

        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        $product->fill($request->all())->save();

        if ($request->has('price')) {
            $product->last_price_update = now();
        }

        if ($request->has('stock')) {
            $product->last_stock_update = now();
        }

        $product->save();

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

        $product->last_stock_update = now();

        $product->save();

        return $product;
    }
}
