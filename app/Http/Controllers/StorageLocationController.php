<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStorageLocationRequest;
use App\Http\Requests\UpdateStorageLocationRequest;
use App\Models\StorageLocation;
use Illuminate\Http\Request;

class StorageLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return $user->storageLocations()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStorageLocationRequest $request)
    {
        $user = $request->user();

        $storageLocation = new StorageLocation($request->validated());

        $user->storageLocations()->save($storageLocation);

        return $storageLocation;
    }

    /**
     * Display the specified resource.
     */
    public function show(StorageLocation $storageLocation)
    {
        return $storageLocation;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStorageLocationRequest $request, StorageLocation $storageLocation)
    {
        $storageLocation->update($request->validated());

        return $storageLocation;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StorageLocation $storageLocation)
    {
        $storageLocation->delete();

        return $storageLocation;
    }
}
