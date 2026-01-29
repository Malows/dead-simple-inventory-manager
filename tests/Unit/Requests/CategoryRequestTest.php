<?php

use App\Http\Requests\Category\StoreRequest;

test('rules keys', function () {
    $keys = array_keys((new StoreRequest)->rules());
    sort($keys);

    expect($keys)->toHaveCount(1)
        ->toEqual(['name']);
});

test('rules values', function () {
    $rules = (new StoreRequest)->rules();

    expect($rules)->toBeArray()
        ->and($rules['name'])->toEqual(['required']);
});
