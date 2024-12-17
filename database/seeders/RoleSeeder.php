<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $empleado = Role::create(['name' => 'empleado']);

        // Crear permisos
        Permission::create(['name' => 'dashboard'])->syncRoles([$admin]);
        Permission::create(['name' => 'usuarios.index']);
        Permission::create(['name' => 'usuarios.create']);


        // Asignar permisos al rol admin
        //$admin->givePermissionTo(Permission::all());
    }
}
