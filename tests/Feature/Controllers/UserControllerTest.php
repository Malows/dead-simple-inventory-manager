<?php

use App\Models\User;
use Database\Seeders\UserSeeder;

test('user profile', function () {
    $this->seed(UserSeeder::class);

    $this->actingAs(User::first(), 'api')
        ->getJson('api/user')
        ->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'email',
        ]);
});
