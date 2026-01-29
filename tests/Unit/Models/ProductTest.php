<?php

use App\Models\Product;

test('product has warning when stock is below min stock warning', function () {
    $product = new Product([
        'stock' => 5,
        'min_stock_warning' => 10,
    ]);

    expect($product->warning)->toBeTrue();
});

test('product has warning when stock equals min stock warning', function () {
    $product = new Product([
        'stock' => 10,
        'min_stock_warning' => 10,
    ]);

    expect($product->warning)->toBeTrue();
});

test('product does not have warning when stock is above min stock warning', function () {
    $product = new Product([
        'stock' => 15,
        'min_stock_warning' => 10,
    ]);

    expect($product->warning)->toBeFalse();
});

test('product warning handles null stock', function () {
    $product = new Product([
        'min_stock_warning' => 10,
    ]);

    expect($product->warning)->toBeTrue();
});

test('product warning handles null min stock warning', function () {
    $product = new Product([
        'stock' => 10,
    ]);

    expect($product->warning)->toBeFalse();
});
