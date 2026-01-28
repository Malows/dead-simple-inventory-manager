<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
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
            return Supplier::whereIn('id', $request->input('ids', []))->with('products')->get();
        } else {
            return Supplier::with('products')->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request): Supplier
    {
        return Supplier::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier): Supplier
    {
        $supplier->load('products');

        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier): Supplier
    {
        $supplier->fill($request->all())->save();

        return $supplier;
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     *
     * @throws \Throwable
     */
    public function destroy(Supplier $supplier): Supplier
    {
        $supplier->delete();

        return $supplier;
    }
}
