<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();

        $suppliers = [
            ['name' => 'Reimer'],
            ['name' => 'Merkansas'],
            ['name' => 'Silva'],
            ['name' => 'Moreno'],
            ['name' => 'Homero'],
        ];

        foreach ($suppliers as $supplier) {
            $supplier['user_id'] = $user->id;
            Supplier::firstOrCreate($supplier);
        }
    }
}
