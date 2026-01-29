<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed(UserSeeder::class);
    $this->user = User::first();
});

test('data index returns correct statistics', function () {
    // Create test data
    Category::factory()->count(3)->create(['user_id' => $this->user->id]);
    Supplier::factory()->count(2)->create(['user_id' => $this->user->id]);

    Product::factory()->count(5)->create([
        'user_id' => $this->user->id,
        'stock' => 10,
        'price' => 100,
    ]);

    Product::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'stock' => 0,
        'price' => 50,
    ]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('api/data')
        ->assertStatus(200)
        ->assertJsonStructure([
            'categories',
            'products',
            'suppliers',
            'different_products',
            'products_amount',
            'money_in_products',
        ]);

    $data = $response->json();

    expect($data['categories'])->toBe(3)
        ->and($data['products'])->toBe(7)
        ->and($data['suppliers'])->toBe(2)
        ->and($data['different_products'])->toBe(5)
        ->and($data['products_amount'])->toBe(50)
        ->and($data['money_in_products'])->toBe(500);
});

test('data index returns zeros when no data exists', function () {
    $response = $this->actingAs($this->user, 'api')
        ->getJson('api/data')
        ->assertStatus(200);

    $data = $response->json();

    expect($data['categories'])->toBe(0)
        ->and($data['products'])->toBe(0)
        ->and($data['suppliers'])->toBe(0)
        ->and($data['different_products'])->toBe(0)
        ->and($data['products_amount'])->toBe(0)
        ->and($data['money_in_products'])->toBe(0);
});
