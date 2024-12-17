<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $userId = auth('web')->id();
        $clientes = Cliente::where('user_id', auth('web')->id())->get();
        return view('clientes.index', compact('clientes'));
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        // Busca clientes que coincidan con el texto ingresado
        $clientes = Cliente::where('cedula', 'like', "%$query%")
            ->orWhere('nombres', 'like', "%$query%")
            ->orWhere('apellidos', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json($clientes);
    }




    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $idUauth = auth('web')->id();

        $request->validate([
            'cedula' => 'required|digits:10|unique:clientes,cedula',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:clientes,email',
        ]);

        Cliente::create([
            'user_id' => $idUauth, // Asociar usuario logueado
            'cedula' => $request->cedula,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado con éxito.');
    }


    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        // Verificar si el usuario autenticado puede modificar este cliente
        if ($cliente->user_id !== auth('web')->id()) {
            return redirect()->route('clientes.index')->with('error', 'No tienes permiso para editar este cliente.');
        }

        // Validación de los datos
        $request->validate([
            'cedula' => 'required|digits:10|unique:clientes,cedula,' . $cliente->id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->id,
        ]);

        // Actualizar solo los campos permitidos
        $cliente->update([
            'cedula' => $request->cedula,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con éxito.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado con éxito.');
    }
}
