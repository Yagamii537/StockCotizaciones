<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Models\DetalleCotizacion;




class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::where('user_id', auth('web')->id())->get();
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
        $a = auth('web')->id();


        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'productos' => 'required|array',
        ]);

        $cotizacion = Cotizacion::create([

            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'total' => array_sum(array_column($request->productos, 'subtotal')),
            'user_id' => $a, // Asociar usuario logueado
        ]);

        foreach ($request->productos as $producto_id => $producto) {
            $cotizacion->detalles()->create([
                'producto_id' => $producto_id,
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio'],
                'subtotal' => $producto['subtotal'],
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización creada con éxito.');
    }

    public function edit(Cotizacion $cotizacione)
    {
        // Cargar las relaciones necesarias para la cotización
        $cotizacione->load('cliente', 'detalles.producto');

        // Obtener los clientes y productos disponibles
        $clientes = Cliente::all();
        $productos = Producto::all();

        // Pasar los datos a la vista
        return view('cotizaciones.edit', compact('cotizacione', 'clientes', 'productos'));
    }


    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'productos' => 'required|array',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.subtotal' => 'required|numeric|min:0',
        ]);

        // Encontrar la cotización
        $cotizacion = Cotizacion::findOrFail($id);

        // Actualizar datos de la cotización
        $cotizacion->update([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'total' => array_sum(array_column($request->productos, 'subtotal')),
        ]);

        // Eliminar los detalles existentes
        $cotizacion->detalles()->delete();

        // Crear los nuevos detalles
        foreach ($request->productos as $productoId => $producto) {
            DetalleCotizacion::create([
                'cotizacion_id' => $cotizacion->id,
                'producto_id' => $productoId,
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio'],
                'subtotal' => $producto['subtotal'],
            ]);
        }

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización actualizada con éxito.');
    }


    public function destroy(Cotizacion $cotizacione)
    {
        $cotizacione->delete();
        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada con éxito.');
    }

    public function generatePDF($id)
    {
        $cotizacion = Cotizacion::with(['cliente', 'detalles.producto'])->findOrFail($id);

        // Pasar datos a la vista del PDF
        $pdf = PDF::loadView('cotizaciones.pdf', compact('cotizacion'));

        // Generar el PDF para descargar
        return $pdf->stream("Cotizacion_{$cotizacion->id}.pdf");
    }
}
