<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): User
    {
        $this->authorize('create', User::class);

        return User::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user): User
    {
        $this->authorize('view', $user);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user): User
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): User
    {
        $this->authorize('delete', $user);

        $user->delete();

        return $user;
    }
}
