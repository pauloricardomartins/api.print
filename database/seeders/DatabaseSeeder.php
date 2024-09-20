<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            CustomerSeeder::class,
            StoreSeeder::class,
            ProductSeeder::class
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'role_id' => Role::Admin,
            'name' => 'Paulo Ricardo',
            'email' => 'paulo@pauloricardo.com.br',
            'password' => '1274',
        ]);
    }
}