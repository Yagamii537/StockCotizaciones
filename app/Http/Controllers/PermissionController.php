<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        // Crear el permiso con el guard_name como 'web'
        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // Asegurar que el guard_name sea 'web'
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permiso creado con éxito.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permiso actualizado con éxito.');
    }

    public function destroy(Permission $permission)
    {

        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permiso eliminado con éxito.');
    }
}
