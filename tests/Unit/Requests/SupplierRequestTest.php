<?php

use App\Http\Requests\SupplierRequest;

test('authorized', function () {
    expect((new SupplierRequest)->authorize())->toBeTrue();
});

test('rules keys', function () {
    $keys = array_keys((new SupplierRequest)->rules());
    sort($keys);

    expect($keys)->toHaveCount(1)
        ->toEqual(['name']);
});

test('rules values', function () {
    $rules = (new SupplierRequest)->rules();

    expect($rules)->toBeArray()
        ->and($rules['name'])->toEqual(['required']);
});
