<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Supplier::class);

        $user = $request->user('api');

        return $user->suppliers()->with('products')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): Supplier
    {
        $user = $request->user('api');

        $supplier = new Supplier($request->validated());

        $user->suppliers()->save($supplier);

        return $supplier;
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier): Supplier
    {
        $this->authorize('view', $supplier);

        $supplier->load('products');

        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Supplier $supplier): Supplier
    {
        $supplier->fill($request->validated())->save();

        return $supplier;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier): Supplier
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        return $supplier;
    }
}
