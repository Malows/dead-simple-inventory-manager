<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
          ['name' => 'Reimer'],
          ['name' => 'Merkansas'],
          ['name' => 'Silva'],
          ['name' => 'Moreno'],
          ['name' => 'Homero'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
