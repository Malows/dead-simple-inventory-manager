<?php

use App\Http\Requests\CategoryRequest;

test('rules keys', function () {
    $keys = array_keys((new CategoryRequest)->rules());
    sort($keys);

    expect($keys)->toHaveCount(1)
        ->toEqual(['name']);
});

test('rules values', function () {
    $rules = (new CategoryRequest)->rules();

    expect($rules)->toBeArray()
        ->and($rules['name'])->toEqual(['required']);
});
