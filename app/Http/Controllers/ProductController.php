<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\Product\UpdateStockRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * Returns all products if user is admin, otherwise only user's products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $user = $request->user('api');

        return Product::forUser($user)
            ->with('supplier', 'categories', 'storageLocation')
            ->orderBy('code')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): Product
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
        $this->authorize('view', $product);

        $product->load('supplier', 'categories', 'storageLocation');

        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product): Product
    {
        $product->update($request->validated());

        $product->categories()->sync($request->get('categories'));

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Exception
     */
    public function destroy(Product $product): Product
    {
        $this->authorize('delete', $product);

        $product->categories()->detach();

        $product->delete();

        return $product;
    }

    /**
     * Update the stock of the specified resource in storage.
     */
    public function updateStock(UpdateStockRequest $request, Product $product): Product
    {
        $this->authorize('updateStock', $product);

        $product->stock = $request->validated()['stock'];

        $product->save();

        return $product;
    }
}
