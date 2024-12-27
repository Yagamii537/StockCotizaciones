<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles primero
        $this->call(RoleSeeder::class);

        // Crear usuarios
        User::create([
            'name' => 'Sebastián Atapuma',
            'email' => 'sebasatapuma@hotmail.com',
            'password' => bcrypt('sebastianAtapuma'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Steven Salinas',
            'email' => 'steeven10@hotmail.com',
            'password' => bcrypt('stevenSalinas'),
        ])->assignRole('empleado');

        User::create([
            'name' => 'Diego Atapuma',
            'email' => 'pumasecuador2018@hotmail.com',
            'password' => bcrypt('diegoAtapuma'),
        ])->assignRole('empleado');

        User::create([
            'name' => 'Dilan Polanco',
            'email' => 'dilanjav_8@hotmail.com',
            'password' => bcrypt('dilanPolanco'),
        ])->assignRole('empleado');

        // Llamar otros seeders después de crear usuarios
        /*
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
            ClienteSeeder::class, // ClienteSeeder depende de los usuarios
        ]);*/
    }
}
