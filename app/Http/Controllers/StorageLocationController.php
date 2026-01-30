<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorageLocation\StoreRequest;
use App\Http\Requests\StorageLocation\UpdateRequest;
use App\Models\StorageLocation;
use Illuminate\Http\Request;

class StorageLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * Returns all storage locations if user is admin, otherwise only user's storage locations.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', StorageLocation::class);

        $user = $request->user('api');

        return StorageLocation::forUser($user)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): StorageLocation
    {
        $user = $request->user('api');

        $storageLocation = new StorageLocation($request->validated());

        $user->storageLocations()->save($storageLocation);

        return $storageLocation;
    }

    /**
     * Display the specified resource.
     */
    public function show(StorageLocation $storageLocation): StorageLocation
    {
        $this->authorize('view', $storageLocation);

        return $storageLocation;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, StorageLocation $storageLocation): StorageLocation
    {
        $storageLocation->update($request->validated());

        return $storageLocation;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StorageLocation $storageLocation): StorageLocation
    {
        $this->authorize('delete', $storageLocation);

        $storageLocation->delete();

        return $storageLocation;
    }
}
