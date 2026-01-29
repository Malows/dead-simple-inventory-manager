<?php

use App\Http\Requests\StorageLocation\StoreRequest;
use App\Http\Requests\StorageLocation\UpdateRequest;

test('store request rules keys', function () {
    $request = new StoreRequest;
    $rules = $request->rules();

    expect(array_keys($rules))->toBe(['name', 'description']);
});

test('store request rules values', function () {
    $request = new StoreRequest;
    $rules = $request->rules();

    expect($rules['name'])->toContain('required', 'string', 'max:255');
    expect($rules['description'])->toContain('nullable', 'string', 'max:1000');
});

test('update request rules keys', function () {
    $request = new UpdateRequest;
    $rules = $request->rules();

    expect(array_keys($rules))->toBe(['name', 'description']);
});

test('update request rules values', function () {
    $request = new UpdateRequest;
    $rules = $request->rules();

    expect($rules['name'])->toContain('required', 'string', 'max:255');
    expect($rules['description'])->toContain('nullable', 'string', 'max:1000');
});
