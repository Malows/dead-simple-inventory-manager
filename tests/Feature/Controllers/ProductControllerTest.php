<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DummyProductSeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_index()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);
        $this->seed(DummyProductSeeder::class);

        $this->actingAs(User::query()->first(), 'api')
            ->getJson('api/products')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'description',
                    'stock',
                    'min_stock_warning',
                    'supplier_id',
                    'supplier',
                    'categories',
                ],
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_store()
    {
        $this->seed(UserSeeder::class);

        $this->assertDatabaseCount('products', 0);

        $data = Product::factory()->make()->toArray();

        $this->actingAs(User::query()->first(), 'api')
            ->postJson('api/products', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'description',
                'stock',
                'min_stock_warning',
            ]);

        $this->assertDatabaseCount('products', 1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_store_with_categories()
    {
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);

        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('category_product', 0);

        $categories = Category::all();

        $data = Product::factory()->make()->toArray();
        $data['categories'] = $categories->pluck('id')->toArray();

        $this->actingAs(User::query()->first(), 'api')
            ->postJson('api/products', $data)
            ->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'description',
                'stock',
                'min_stock_warning',
            ]);

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('category_product', $categories->count());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_show()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);
        $this->seed(DummyProductSeeder::class);

        $product = Product::first();

        $this->actingAs(User::query()->first(), 'api')
            ->getJson("api/products/{$product->uuid}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'description',
                'stock',
                'min_stock_warning',
                'supplier_id',
                'supplier' => ['name'],
                'categories' => [
                    '*' => ['name'],
                ],
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_update()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);
        $this->seed(DummyProductSeeder::class);

        $product = Product::first();

        $data = $product->toArray();
        $data['name'] = 'TEST NAME';

        $this->actingAs(User::query()->first(), 'api')
            ->putJson("api/products/{$product->uuid}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'description',
                'stock',
                'min_stock_warning',
                'supplier_id',
            ]);

        $this->assertDatabaseHas('products', ['name' => 'TEST NAME']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_update_with_categories()
    {
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(SupplierSeeder::class);
        $this->seed(DummyProductSeeder::class);

        $product = Product::first();

        $categories = Category::all();

        $data = $product->toArray();
        $data['name'] = 'TEST NAME';
        $data['categories'] = $categories->pluck('id')->toArray();

        $this->assertDatabaseCount('category_product', 0);

        $this->actingAs(User::query()->first(), 'api')
            ->putJson("api/products/{$product->uuid}", $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'description',
                'stock',
                'min_stock_warning',
                'supplier_id',
            ]);

        $this->assertDatabaseHas('products', ['name' => 'TEST NAME']);
        $this->assertDatabaseCount('category_product', $categories->count());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_delete()
    {
        $this->seed(UserSeeder::class);
        $this->seed(SupplierSeeder::class);
        $this->seed(DummyProductSeeder::class);

        $product = Product::first();

        $this->actingAs(User::query()->first(), 'api')
            ->deleteJson("api/products/{$product->uuid}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'description',
                'stock',
                'min_stock_warning',
                'supplier_id',
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
