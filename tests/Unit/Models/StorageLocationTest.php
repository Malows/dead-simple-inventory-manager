<?php

use App\Models\StorageLocation;

test('storage location has fillable attributes', function () {
    $location = new StorageLocation([
        'name' => 'Test Location',
        'description' => 'Test Description',
        'user_id' => 1,
    ]);

    expect($location->name)->toBe('Test Location')
        ->and($location->description)->toBe('Test Description')
        ->and($location->user_id)->toBe(1);
});
