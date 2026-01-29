<?php

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;

beforeEach(function () {
    $this->seed(UserSeeder::class);
    $this->user = User::first();
});

test('categories index', function () {
    $this->seed(CategorySeeder::class);

    $this->actingAs($this->user, 'api')
        ->getJson('api/categories')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => ['name', 'user_id'],
        ]);
});

test('categories store', function () {
    $this->assertDatabaseCount('categories', 0);

    $data = Category::factory()->make(['user_id' => $this->user->id])->toArray();

    $this->actingAs($this->user, 'api')
        ->postJson('api/categories', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'name',
            'user_id',
        ]);

    $this->assertDatabaseCount('categories', 1);
});

test('categories show', function () {
    $this->seed(CategorySeeder::class);

    $category = Category::first();

    $this->actingAs($this->user, 'api')
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

    $this->actingAs($this->user, 'api')
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

    $this->actingAs($this->user, 'api')
        ->deleteJson("api/categories/{$category->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
        ]);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('admin can see all categories', function () {
    // Create admin user
    $admin = User::factory()->create(['role' => 'admin']);

    // Create categories for different users
    $category1 = Category::factory()->create(['user_id' => $this->user->id]);
    $category2 = Category::factory()->create(['user_id' => $admin->id]);
    $anotherUser = User::factory()->create();
    $category3 = Category::factory()->create(['user_id' => $anotherUser->id]);

    $response = $this->actingAs($admin, 'api')
        ->getJson('api/categories')
        ->assertStatus(200);

    $categories = $response->json();

    // Admin should see all 3 categories
    expect(count($categories))->toBe(3);
});

test('non-admin user can only see their own categories', function () {
    // Create categories for different users
    $myCategory = Category::factory()->create(['user_id' => $this->user->id]);

    $anotherUser = User::factory()->create();
    $otherCategory = Category::factory()->create(['user_id' => $anotherUser->id]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('api/categories')
        ->assertStatus(200);

    $categories = $response->json();

    // Non-admin should only see their own category
    expect(count($categories))->toBe(1);
    expect($categories[0]['uuid'])->toBe($myCategory->uuid->toString());
});

