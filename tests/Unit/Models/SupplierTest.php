<?php

use App\Models\Supplier;
use App\Models\User;

test('supplier has fillable attributes', function () {
    $supplier = new Supplier([
        'name' => 'Test Supplier',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'web' => 'https://example.com',
        'user_id' => 1,
    ]);

    expect($supplier->name)->toBe('Test Supplier')
        ->and($supplier->email)->toBe('test@example.com')
        ->and($supplier->phone)->toBe('1234567890')
        ->and($supplier->address)->toBe('Test Address')
        ->and($supplier->web)->toBe('https://example.com')
        ->and($supplier->user_id)->toBe(1);
});
