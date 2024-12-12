<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with('cliente')->get();
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('cotizaciones.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
{
    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'fecha' => 'required|date',
        'productos' => 'required|array',
        'productos.*.id' => 'required|exists:productos,id',
        'productos.*.cantidad' => 'required|integer|min:1',
    ]);

    $cotizacion = Cotizacion::create([
        'cliente_id' => $request->cliente_id,
        'fecha' => $request->fecha,
        'total' => 0,
    ]);

    $total = 0;
    foreach ($request->productos as $producto) {
        $subtotal = $producto['cantidad'] * $producto['precio'];
        $cotizacion->detalles()->create([
            'producto_id' => $producto['id'],
            'cantidad' => $producto['cantidad'],
            'precio_unitario' => $producto['precio'],
            'subtotal' => $subtotal,
        ]);
        $total += $subtotal;
    }

    $cotizacion->update(['total' => $total]);

    return redirect()->route('cotizaciones.index')->with('success', 'Cotización creada con éxito.');
}


    public function edit(Cotizacion $cotizacion)
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('cotizaciones.edit', compact('cotizacion', 'clientes', 'productos'));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        // Similar a store, actualizando los datos
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada con éxito.');
    }
}
