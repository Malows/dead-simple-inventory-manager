<?php

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed(UserSeeder::class);
});

test('categories index', function () {
    $this->seed(CategorySeeder::class);

    $this->actingAs(User::first(), 'api')
        ->getJson('api/categories')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['name'],
        ]);
});

test('categories store', function () {
    $this->assertDatabaseCount('categories', 0);

    $data = Category::factory()->make()->toArray();

    $this->actingAs(User::first(), 'api')
        ->postJson('api/categories', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'name',
        ]);

    $this->assertDatabaseCount('categories', 1);
});

test('categories show', function () {
    $this->seed(CategorySeeder::class);

    $category = Category::first();

    $this->actingAs(User::first(), 'api')
        ->getJson("api/categories/{$category->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'products',
        ]);
});

test('categories update', function () {
    $this->seed(CategorySeeder::class);

    $category = Category::first();

    $this->actingAs(User::first(), 'api')
        ->putJson("api/categories/{$category->uuid}", ['name' => 'TEST NAME'])
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
        ]);

    $this->assertDatabaseHas('categories', ['name' => 'TEST NAME']);
});

test('categories delete', function () {
    $this->seed(CategorySeeder::class);

    $category = Category::first();

    $this->actingAs(User::first(), 'api')
        ->deleteJson("api/categories/{$category->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
        ]);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
