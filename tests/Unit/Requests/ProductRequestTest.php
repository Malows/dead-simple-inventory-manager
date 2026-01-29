<?php

use App\Http\Requests\ProductRequest;

test('rules keys', function () {
    $keys = array_keys((new ProductRequest)->rules());
    sort($keys);

    expect($keys)->toHaveCount(9)
        ->toEqual([
            'categories',
            'categories.*',
            'code',
            'description',
            'min_stock_warning',
            'name',
            'price',
            'stock',
            'supplier_id',
        ]);
});

test('rules values', function () {
    $rules = (new ProductRequest)->rules();

    expect($rules)->toBeArray()
        ->and($rules['categories'])->toEqual(['array'])
        ->and($rules['categories.*'])->toEqual(['exists:categories,id'])
        ->and($rules['code'])->toEqual(['nullable'])
        ->and($rules['description'])->toEqual(['nullable', 'string'])
        ->and($rules['min_stock_warning'])->toEqual(['nullable', 'integer'])
        ->and($rules['name'])->toEqual(['required'])
        ->and($rules['price'])->toEqual(['nullable', 'numeric'])
        ->and($rules['stock'])->toEqual(['required', 'integer'])
        ->and($rules['supplier_id'])->toEqual(['nullable', 'exists:suppliers,id']);
});
