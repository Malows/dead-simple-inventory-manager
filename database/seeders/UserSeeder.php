<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'uuid' => Str::uuid(),
                'name' => env('ADMIN_USER_NAME'),
                'email' => env('ADMIN_USER_EMAIL'),
                'password' => Hash::make(env('ADMIN_USER_PASSWORD')),
            ],
        ];

        if (DB::table('users')->count() == 0) {
            DB::table('users')->insert($users);
        }
    }
}
