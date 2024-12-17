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
            'name' => 'Mauricio Peñafiel',
            'email' => 'admin@correo.com',
            'password' => bcrypt('123456789'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Empleado 1',
            'email' => 'empleado1@correo.com',
            'password' => bcrypt('123456789'),
        ])->assignRole('empleado');

        User::create([
            'name' => 'Empleado 2',
            'email' => 'empleado2@correo.com',
            'password' => bcrypt('123456789'),
        ])->assignRole('empleado');

        // Llamar otros seeders después de crear usuarios
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
            ClienteSeeder::class, // ClienteSeeder depende de los usuarios
        ]);
    }
}
