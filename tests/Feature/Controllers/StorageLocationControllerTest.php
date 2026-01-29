<?php

use App\Models\StorageLocation;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
    $this->anotherUser = User::factory()->create();
});

// Index tests
test('authenticated user can list their storage locations', function () {
    StorageLocation::factory()->count(3)->create(['user_id' => $this->user->id]);
    StorageLocation::factory()->count(2)->create(['user_id' => $this->anotherUser->id]);

    $response = $this->actingAs($this->user, 'api')
        ->getJson('api/storage-locations')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'uuid',
                'name',
                'description',
                'user_id',
            ],
        ]);

    expect($response->json())->toHaveCount(3);
});

test('guest cannot list storage locations', function () {
    $this->getJson('api/storage-locations')
        ->assertStatus(401);
});

// Store tests
test('user can create storage location', function () {
    $this->assertDatabaseCount('storage_locations', 0);

    $data = [
        'name' => 'Main Warehouse',
        'description' => 'Primary storage facility',
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/storage-locations', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'uuid',
            'name',
            'description',
            'user_id',
        ])
        ->assertJson([
            'name' => 'Main Warehouse',
            'description' => 'Primary storage facility',
            'user_id' => $this->user->id,
        ]);

    $this->assertDatabaseCount('storage_locations', 1);
});

test('user can create storage location without description', function () {
    $data = [
        'name' => 'Secondary Storage',
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/storage-locations', $data)
        ->assertStatus(201)
        ->assertJson([
            'name' => 'Secondary Storage',
        ]);
});

test('name is required when creating storage location', function () {
    $data = [
        'description' => 'Some description',
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/storage-locations', $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('name must not exceed 255 characters', function () {
    $data = [
        'name' => str_repeat('a', 256),
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/storage-locations', $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('description must not exceed 1000 characters', function () {
    $data = [
        'name' => 'Valid Name',
        'description' => str_repeat('a', 1001),
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/storage-locations', $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('guest cannot create storage location', function () {
    $data = [
        'name' => 'Test Location',
    ];

    $this->postJson('api/storage-locations', $data)
        ->assertStatus(401);
});

// Show tests
test('admin can view any storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->admin, 'api')
        ->getJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(200)
        ->assertJson([
            'id' => $storageLocation->id,
            'name' => $storageLocation->name,
        ]);
});

test('user can view own storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user, 'api')
        ->getJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(200)
        ->assertJson([
            'id' => $storageLocation->id,
            'name' => $storageLocation->name,
        ]);
});

test('user cannot view other users storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->anotherUser->id]);

    $this->actingAs($this->user, 'api')
        ->getJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(403);
});

test('guest cannot view storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $this->getJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(401);
});

// Update tests
test('admin can update any storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
    ];

    $this->actingAs($this->admin, 'api')
        ->putJson("api/storage-locations/{$storageLocation->uuid}", $data)
        ->assertStatus(200)
        ->assertJson([
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ]);

    $this->assertDatabaseHas('storage_locations', [
        'id' => $storageLocation->id,
        'name' => 'Updated Name',
    ]);
});

test('user can update own storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'name' => 'My Updated Location',
        'description' => 'My updated description',
    ];

    $this->actingAs($this->user, 'api')
        ->putJson("api/storage-locations/{$storageLocation->uuid}", $data)
        ->assertStatus(200)
        ->assertJson([
            'name' => 'My Updated Location',
        ]);
});

test('user cannot update other users storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->anotherUser->id]);

    $data = [
        'name' => 'Attempted Update',
    ];

    $this->actingAs($this->user, 'api')
        ->putJson("api/storage-locations/{$storageLocation->uuid}", $data)
        ->assertStatus(403);
});

test('guest cannot update storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $data = [
        'name' => 'Attempted Update',
    ];

    $this->putJson("api/storage-locations/{$storageLocation->uuid}", $data)
        ->assertStatus(401);
});

// Delete tests
test('admin can delete any storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->admin, 'api')
        ->deleteJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('storage_locations', [
        'id' => $storageLocation->id,
    ]);
});

test('user can delete own storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user, 'api')
        ->deleteJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('storage_locations', [
        'id' => $storageLocation->id,
    ]);
});

test('user cannot delete other users storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->anotherUser->id]);

    $this->actingAs($this->user, 'api')
        ->deleteJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(403);
});

test('guest cannot delete storage location', function () {
    $storageLocation = StorageLocation::factory()->create(['user_id' => $this->user->id]);

    $this->deleteJson("api/storage-locations/{$storageLocation->uuid}")
        ->assertStatus(401);
});
