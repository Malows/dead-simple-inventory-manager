<?php

use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed(UserSeeder::class);
    $this->user = User::first();
});

test('suppliers index', function () {
    $this->seed(SupplierSeeder::class);

    $this->actingAs($this->user, 'api')
        ->getJson('api/suppliers')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['name', 'user_id'],
        ]);
});

test('suppliers store', function () {
    $this->assertDatabaseCount('suppliers', 0);

    $data = Supplier::factory()->make(['user_id' => $this->user->id])->toArray();

    $this->actingAs($this->user, 'api')
        ->postJson('api/suppliers', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'name',
            'user_id',
        ]);

    $this->assertDatabaseCount('suppliers', 1);
});

test('suppliers show', function () {
    $this->seed(SupplierSeeder::class);

    $supplier = Supplier::first();

    $this->actingAs($this->user, 'api')
        ->getJson("api/suppliers/{$supplier->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'products',
        ]);
});

test('suppliers update', function () {
    $this->seed(SupplierSeeder::class);

    $supplier = Supplier::first();

    $this->actingAs($this->user, 'api')
        ->putJson("api/suppliers/{$supplier->uuid}", ['name' => 'TEST NAME'])
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
        ]);

    $this->assertDatabaseHas('suppliers', ['name' => 'TEST NAME']);
});

test('suppliers delete', function () {
    $this->seed(SupplierSeeder::class);

    $supplier = Supplier::first();

    $this->actingAs($this->user, 'api')
        ->deleteJson("api/suppliers/{$supplier->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
        ]);

    $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
});
