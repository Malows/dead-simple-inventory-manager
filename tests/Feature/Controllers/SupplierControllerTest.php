<?php

namespace Tests\Feature\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_suppliers_index()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);

        $this->actingAs(User::query()->first(), 'api')
            ->getJson('api/suppliers')
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
    public function test_suppliers_store()
    {
        $this->seed(UserSeeder::class);

        $this->assertDatabaseCount('suppliers', 0);

        $data = Supplier::factory()->make()->toArray();

        $this->actingAs(User::query()->first(), 'api')
            ->postJson('api/suppliers', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
            ]);

        $this->assertDatabaseCount('suppliers', 1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_suppliers_show()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);

        $supplier = Supplier::first();

        $this->actingAs(User::query()->first(), 'api')
            ->getJson("api/suppliers/{$supplier->uuid}")
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
    public function test_suppliers_update()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);

        $supplier = Supplier::first();

        $this->actingAs(User::query()->first(), 'api')
            ->putJson("api/suppliers/{$supplier->uuid}", ['name' => 'TEST NAME'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
            ]);

        $this->assertDatabaseHas('suppliers', ['name' => 'TEST NAME']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_suppliers_delete()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);

        $supplier = Supplier::first();

        $this->actingAs(User::query()->first(), 'api')
            ->deleteJson("api/suppliers/{$supplier->uuid}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
            ]);

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
