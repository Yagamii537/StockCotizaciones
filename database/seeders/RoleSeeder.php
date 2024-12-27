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
        Permission::create(['name' => 'dashboard'])->syncRoles([$admin, $empleado]);

        Permission::create(['name' => 'categorias.index'])->syncRoles([$admin, $empleado]);
        Permission::create(['name' => 'categorias.create'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'categorias.edit'])->syncRoles([$admin, $empleado]);;

        Permission::create(['name' => 'clientes.index'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'clientes.create'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'clientes.edit'])->syncRoles([$admin, $empleado]);;

        Permission::create(['name' => 'users.index'])->syncRoles([$admin,]);;
        Permission::create(['name' => 'users.create'])->syncRoles([$admin,]);;
        Permission::create(['name' => 'users.edit'])->syncRoles([$admin,]);;

        Permission::create(['name' => 'roles.index'])->syncRoles([$admin,]);;
        Permission::create(['name' => 'roles.create'])->syncRoles([$admin,]);;
        Permission::create(['name' => 'roles.edit'])->syncRoles([$admin,]);;

        Permission::create(['name' => 'permissions.index'])->syncRoles([$admin,]);;
        Permission::create(['name' => 'permissions.create'])->syncRoles([$admin,]);;
        Permission::create(['name' => 'permissions.edit'])->syncRoles([$admin,]);;

        Permission::create(['name' => 'inventarios.index'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'inventarios.create'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'inventarios.edit'])->syncRoles([$admin, $empleado]);;

        Permission::create(['name' => 'cotizaciones.index'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'cotizaciones.create'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'cotizaciones.edit'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'cotizaciones.entregado'])->syncRoles([$admin, $empleado]);;
        Permission::create(['name' => 'cotizaciones.pdf'])->syncRoles([$admin, $empleado]);;

        Permission::create(['name' => 'reportes.productos'])->syncRoles([$admin, $empleado]);;


        // Asignar permisos al rol admin
        //$admin->givePermissionTo(Permission::all());
    }
}
