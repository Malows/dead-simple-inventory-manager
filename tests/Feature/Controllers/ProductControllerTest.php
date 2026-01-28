<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DummyProductSeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed(UserSeeder::class);
});

test('products index', function () {
    $this->seed(SupplierSeeder::class);
    $this->seed(DummyProductSeeder::class);

    $this->actingAs(User::first(), 'api')
        ->getJson('api/products')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'name',
                'description',
                'stock',
                'min_stock_warning',
                'supplier_id',
                'supplier',
                'categories',
            ],
        ]);
});

test('products store', function () {
    $this->assertDatabaseCount('products', 0);

    $data = Product::factory()->make()->toArray();

    $this->actingAs(User::first(), 'api')
        ->postJson('api/products', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'name',
            'description',
            'stock',
            'min_stock_warning',
        ]);

    $this->assertDatabaseCount('products', 1);
});

test('products store with categories', function () {
    $this->seed(CategorySeeder::class);

    $this->assertDatabaseCount('products', 0);
    $this->assertDatabaseCount('category_product', 0);

    $categories = Category::all();

    $data = Product::factory()->make()->toArray();
    $data['categories'] = $categories->pluck('id')->toArray();

    $this->actingAs(User::first(), 'api')
        ->postJson('api/products', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'name',
            'description',
            'stock',
            'min_stock_warning',
        ]);

    $this->assertDatabaseCount('products', 1);
    $this->assertDatabaseCount('category_product', $categories->count());
});

test('products show', function () {
    $this->seed(SupplierSeeder::class);
    $this->seed(DummyProductSeeder::class);

    $product = Product::first();

    $this->actingAs(User::first(), 'api')
        ->getJson("api/products/{$product->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'description',
            'stock',
            'min_stock_warning',
            'supplier_id',
            'supplier' => ['name'],
            'categories' => [
                '*' => ['name'],
            ],
        ]);
});

test('products update', function () {
    $this->seed(SupplierSeeder::class);
    $this->seed(DummyProductSeeder::class);

    $product = Product::first();

    $data = $product->toArray();
    $data['name'] = 'TEST NAME';

    $this->actingAs(User::first(), 'api')
        ->putJson("api/products/{$product->uuid}", $data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'description',
            'stock',
            'min_stock_warning',
            'supplier_id',
        ]);

    $this->assertDatabaseHas('products', ['name' => 'TEST NAME']);
});

test('products update with categories', function () {
    $this->seed(CategorySeeder::class);
    $this->seed(SupplierSeeder::class);
    $this->seed(DummyProductSeeder::class);

    $product = Product::first();

    $categories = Category::all();

    $data = $product->toArray();
    $data['name'] = 'TEST NAME';
    $data['categories'] = $categories->pluck('id')->toArray();

    $this->assertDatabaseCount('category_product', 0);

    $this->actingAs(User::first(), 'api')
        ->putJson("api/products/{$product->uuid}", $data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'description',
            'stock',
            'min_stock_warning',
            'supplier_id',
        ]);

    $this->assertDatabaseHas('products', ['name' => 'TEST NAME']);
    $this->assertDatabaseCount('category_product', $categories->count());
});

test('products delete', function () {
    $this->seed(SupplierSeeder::class);
    $this->seed(DummyProductSeeder::class);

    $product = Product::first();

    $this->actingAs(User::first(), 'api')
        ->deleteJson("api/products/{$product->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'description',
            'stock',
            'min_stock_warning',
            'supplier_id',
        ]);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});
