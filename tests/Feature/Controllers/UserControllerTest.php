<?php

use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

// Index tests
test('admin can list all users', function () {
    $this->actingAs($this->admin, 'api')
        ->getJson('api/users')
        ->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'role',
            ],
        ]);
});

test('non-admin cannot list users', function () {
    $this->actingAs($this->user, 'api')
        ->getJson('api/users')
        ->assertStatus(403);
});

test('guest cannot list users', function () {
    $this->getJson('api/users')
        ->assertStatus(401);
});

// Store tests
test('admin can create user', function () {
    $this->assertDatabaseCount('users', 2); // admin + user from beforeEach

    $data = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'user',
    ];

    $this->actingAs($this->admin, 'api')
        ->postJson('api/users', $data)
        ->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'role',
        ]);

    $this->assertDatabaseCount('users', 3);
});

test('non-admin cannot create user', function () {
    $data = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'role' => 'user',
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/users', $data)
        ->assertStatus(403);
});

test('guest cannot create user', function () {
    $data = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'role' => 'user',
    ];

    $this->getJson('api/users')
        ->assertStatus(401);
});

// Show tests
test('admin can view user', function () {
    $this->actingAs($this->admin, 'api')
        ->getJson("api/users/{$this->user->uuid}")
        ->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'role',
        ]);
});

test('non-admin cannot view user', function () {
    $anotherUser = User::factory()->create();

    $this->actingAs($this->user, 'api')
        ->getJson("api/users/{$anotherUser->uuid}")
        ->assertStatus(403);
});

test('guest cannot view user', function () {
    $this->getJson("api/users/{$this->user->uuid}")
        ->assertStatus(401);
});

// Update tests
test('admin can update user', function () {
    $data = [
        'name' => 'Updated Name',
        'email' => $this->user->email,
        'role' => $this->user->role,
    ];

    $this->actingAs($this->admin, 'api')
        ->putJson("api/users/{$this->user->uuid}", $data)
        ->assertStatus(200)
        ->assertJson([
            'name' => 'Updated Name',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => 'Updated Name',
    ]);
});

test('non-admin cannot update user', function () {
    $anotherUser = User::factory()->create();

    $data = [
        'name' => 'Updated Name',
        'email' => $anotherUser->email,
        'role' => $anotherUser->role,
    ];

    $this->actingAs($this->user, 'api')
        ->putJson("api/users/{$anotherUser->uuid}", $data)
        ->assertStatus(403);
});

test('guest cannot update user', function () {
    $data = [
        'name' => 'Updated Name',
        'email' => $this->user->email,
        'role' => $this->user->role,
    ];

    $this->putJson("api/users/{$this->user->uuid}", $data)
        ->assertStatus(401);
});

// Delete tests
test('admin can delete user', function () {
    $userToDelete = User::factory()->create();

    $this->actingAs($this->admin, 'api')
        ->deleteJson("api/users/{$userToDelete->uuid}")
        ->assertStatus(200);

    $this->assertDatabaseMissing('users', [
        'id' => $userToDelete->id,
        'deleted_at' => null,
    ]);
});

test('non-admin cannot delete user', function () {
    $userToDelete = User::factory()->create();

    $this->actingAs($this->user, 'api')
        ->deleteJson("api/users/{$userToDelete->uuid}")
        ->assertStatus(403);
});

test('guest cannot delete user', function () {
    $this->deleteJson("api/users/{$this->user->uuid}")
        ->assertStatus(401);
});

// Update password tests
test('admin can update user password', function () {
    $userToUpdate = User::factory()->create();

    $data = [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ];

    $this->actingAs($this->admin, 'api')
        ->putJson("api/users/{$userToUpdate->uuid}/password", $data)
        ->assertStatus(200);

    $userToUpdate->refresh();

    expect(\Illuminate\Support\Facades\Hash::check('newpassword123', $userToUpdate->password))->toBeTrue();
});

test('non-admin cannot update user password', function () {
    $userToUpdate = User::factory()->create();

    $data = [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ];

    $this->actingAs($this->user, 'api')
        ->putJson("api/users/{$userToUpdate->uuid}/password", $data)
        ->assertStatus(403);
});

test('guest cannot update user password', function () {
    $data = [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ];

    $this->putJson("api/users/{$this->user->uuid}/password", $data)
        ->assertStatus(401);
});

// Relationship tests
test('storage location belongs to user', function () {
    $user = User::factory()->create();
    $location = \App\Models\StorageLocation::factory()->create(['user_id' => $user->id]);

    expect($location->user)->toBeInstanceOf(User::class);
    expect($location->user->id)->toBe($user->id);
});

test('storage location has products', function () {
    $location = \App\Models\StorageLocation::factory()->create();

    expect($location->products)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});
