<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * Returns all categories if user is admin, otherwise only user's categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $user = $request->user('api');

        return Category::forUser($user)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): Category
    {
        $user = $request->user('api');

        $category = new Category($request->validated());

        $user->categories()->save($category);

        return $category;
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): Category
    {
        $this->authorize('view', $category);

        $category->load('products');

        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Category $category): Category
    {
        $category->fill($request->validated())->save();

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Exception
     */
    public function destroy(Category $category): Category
    {
        $this->authorize('delete', $category);

        $category->products()->detach();

        $category->delete();

        return $category;
    }
}
