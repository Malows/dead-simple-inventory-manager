<?php

use App\Models\User;
use Database\Seeders\UserSeeder;

test('user profile', function () {
    $this->seed(UserSeeder::class);
    $user = User::first();

    $this->actingAs($user, 'api')
        ->getJson('api/user')
        ->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);
});
