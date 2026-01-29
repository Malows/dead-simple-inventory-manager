<?php

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(UserSeeder::class);
    $this->user = User::first();
});

test('user can get their profile', function () {
    $response = $this->actingAs($this->user, 'api')
        ->getJson('api/profile')
        ->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'role',
        ]);

    expect($response->json('id'))->toBe($this->user->id);
});

test('guest cannot get profile', function () {
    $this->getJson('api/profile')
        ->assertStatus(401);
});

test('user can update their profile', function () {
    $data = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ];

    $this->actingAs($this->user, 'api')
        ->postJson('api/profile', $data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);

    $this->user->refresh();
    expect($this->user->name)->toBe('Updated Name')
        ->and($this->user->email)->toBe('updated@example.com');
});

test('guest cannot update profile', function () {
    $data = [
        'name' => 'Updated Name',
    ];

    $this->postJson('api/profile', $data)
        ->assertStatus(401);
});

test('user can update their password', function () {
    $newPassword = 'newpassword123';

    $this->actingAs($this->user, 'api')
        ->postJson('api/profile/password', ['password' => $newPassword])
        ->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);

    $this->user->refresh();
    expect(Hash::check($newPassword, $this->user->password))->toBeTrue();
});

test('guest cannot update password', function () {
    $this->postJson('api/profile/password', ['password' => 'newpassword'])
        ->assertStatus(401);
});
