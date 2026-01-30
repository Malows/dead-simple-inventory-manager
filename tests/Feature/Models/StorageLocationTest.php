<?php

use App\Models\StorageLocation;
use App\Models\User;

test('storage location belongs to user', function () {
    $user = User::factory()->create();
    $location = StorageLocation::factory()->create(['user_id' => $user->id]);

    expect($location->user)->toBeInstanceOf(User::class);
    expect($location->user->id)->toBe($user->id);
});

test('storage location has products', function () {
    $location = StorageLocation::factory()->create();

    expect($location->products)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});
