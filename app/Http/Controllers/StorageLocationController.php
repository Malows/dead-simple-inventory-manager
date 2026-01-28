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
     */
    public function index(Request $request)
    {
        $user = $request->user('api');

        return $user->storageLocations()->get();
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
        $storageLocation->delete();

        return $storageLocation;
    }
}
