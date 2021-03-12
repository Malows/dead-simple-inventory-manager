<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_categories_index()
    {
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $this->actingAs(User::query()->first(), 'api')
            ->getJson('api/categories')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['name'],
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_categories_store()
    {
        $this->seed(UserSeeder::class);

        $this->assertDatabaseCount('categories', 0);

        $data = Category::factory()->make()->toArray();

        $this->actingAs(User::query()->first(), 'api')
            ->postJson('api/categories', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
            ]);

        $this->assertDatabaseCount('categories', 1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_categories_show()
    {
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $category = Category::first();

        $this->actingAs(User::query()->first(), 'api')
            ->getJson("api/categories/{$category->uuid}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'products',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_categories_update()
    {
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $category = Category::first();

        $this->actingAs(User::query()->first(), 'api')
            ->putJson("api/categories/{$category->uuid}", ['name' => 'TEST NAME'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
            ]);

        $this->assertDatabaseHas('categories', ['name' => 'TEST NAME']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_categories_delete()
    {
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $category = Category::first();

        $this->actingAs(User::query()->first(), 'api')
            ->deleteJson("api/categories/{$category->uuid}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
            ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
