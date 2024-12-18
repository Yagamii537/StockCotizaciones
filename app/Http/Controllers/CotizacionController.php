<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Models\DetalleCotizacion;
use Illuminate\Support\Carbon;




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

        // Verificar que el usuario autenticado solo pueda editar su cotización
        if ($cotizacione->user_id !== auth('web')->id()) {
            return redirect()->route('cotizaciones.index')->with('error', 'No tienes permiso para editar esta cotización.');
        }

        // Pasar los productos y cotización a la vista
        $productos = Producto::all();

        return view('cotizaciones.edit', compact('cotizacione', 'productos'));
    }


    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.subtotal' => 'required|numeric|min:0',
        ]);

        // Obtener la fecha, si no viene del formulario usa la fecha actual
        $fecha = $request->input('fecha') ?? Carbon::now()->toDateString();

        // Encontrar la cotización
        $cotizacion = Cotizacion::findOrFail($id);

        // Actualizar los datos de la cotización
        $cotizacion->update([
            'cliente_id' => $request->cliente_id,
            'fecha' => $fecha,
            'total' => collect($request->productos)->sum('subtotal'),
        ]);

        // Eliminar los detalles existentes
        $cotizacion->detalles()->delete();

        // Crear los nuevos detalles
        foreach ($request->productos as $producto) {
            $cotizacion->detalles()->create([
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio'],
                'subtotal' => $producto['subtotal'],
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización actualizada correctamente.');
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
