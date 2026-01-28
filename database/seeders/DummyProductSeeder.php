<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        
        foreach (Supplier::all() as $supplier) {
            Product::factory()->count(5)->for($supplier)->for($user)->create();
        }
    }
}
