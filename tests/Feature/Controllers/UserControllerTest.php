<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_profile()
    {
        $this->seed(UserSeeder::class);

        $this->actingAs(User::first(), 'api')
            ->getJson('api/user')
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email',
            ]);
    }
}
