<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Producto::with('categoria')->get();
        return view('inventarios.index', compact('inventarios'));
    }

    public function create()
    {
        $categorias = Categoria::all(); // Trae todas las categorías de la base de datos
        return view('inventarios.create', compact('categorias')); // Pasa las categorías a la vista
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

        return redirect()->route('inventarios.index')->with('success', 'Producto creado con éxito.');
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // Si no hay término de búsqueda, devolver una respuesta vacía
            if (!$query) {
                return response()->json([], 200);
            }

            // Buscar productos por nombre o descripción
            $productos = Producto::where('nombre', 'like', "%$query%")
                ->orWhere('descripcion', 'like', "%$query%")
                ->limit(10)
                ->get();

            // Devuelve los productos encontrados en formato JSON
            return response()->json($productos, 200);
        } catch (\Exception $e) {
            // Devuelve el mensaje de error si ocurre una excepción
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }









    public function show($id) {}

    public function edit(Producto $inventario)
    {
        $categorias = Categoria::all();
        return view('inventarios.edit', compact('inventario', 'categorias'));
    }





    public function update(Request $request, Producto $inventario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $inventario->update($request->all());

        return redirect()->route('inventarios.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy(Producto $inventario)
    {
        $inventario->delete(); // Elimina el producto
        return redirect()->route('inventarios.index')->with('success', 'Producto eliminado con éxito.');
    }
}
