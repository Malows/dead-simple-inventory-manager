<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function show(User $user): User
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
    public function destroy(User $user): User
    {
        $this->authorize('delete', $user);

        $user->delete();

        return $user;
    }
}
