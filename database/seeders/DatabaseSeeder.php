<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            CategoriaSeeder::class,
            ProductoSeeder::class,
            ClienteSeeder::class
        ]);

        User::create([
            'name' => 'Mauricio Peñafiel',
            'email' => 'admin@correo.com',
            'password' => bcrypt('123456789')
        ])->assignRole('admin');;
    }
}
