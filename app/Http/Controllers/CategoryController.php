<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\IdRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IdRequest $request
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(IdRequest $request)
    {
        if ($request->has('ids')) {
            return Category::whereIn('id', $request->input('ids', []))->get();
        } else {
            return Category::all();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryRequest  $request
     *
     * @return Category
     */
    public function store(CategoryRequest $request): Category
    {
        return Category::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     *
     * @return Category
     */
    public function show(Category $category): Category
    {
        $category->load('products');

        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryRequest  $request
     * @param  Category  $category
     *
     * @return Category
     */
    public function update(CategoryRequest $request, Category $category): Category
    {
        $category->fill($request->all())->save();

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     *
     * @return Category
     *
     * @throws \Exception
     */
    public function destroy(Category $category): Category
    {
        $category->products()->detach();

        $category->delete();

        return $category;
    }
}
