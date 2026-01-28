<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        Category::factory()->count(10)->for($user)->create();
    }
}
