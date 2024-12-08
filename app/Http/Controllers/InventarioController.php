<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return view('inventario.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all(); // Trae todas las categorías de la base de datos
        return view('inventario.create', compact('categorias')); // Pasa las categorías a la vista
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Producto::create($request->all());

        return redirect()->route('inventario.index')->with('success', 'Producto creado con éxito.');
    }
}
