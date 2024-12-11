<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|digits:10|unique:clientes,cedula',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:clientes,email',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado con éxito.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'cedula' => 'required|digits:10|unique:clientes,cedula,' . $cliente->id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->id,
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con éxito.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado con éxito.');
    }
}
