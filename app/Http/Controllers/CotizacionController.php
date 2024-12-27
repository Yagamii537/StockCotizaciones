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
        $cotizaciones = Cotizacion::where('user_id', auth('web')->id())
            ->whereBetween('estado', [1, 4]) // Filtrar estados entre 1 y 4
            ->get();
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function entregado()
    {
        $cotizaciones = Cotizacion::where('user_id', auth('web')->id())
            ->where('estado', 5) // Solo estado 5
            ->get();

        return view('cotizaciones.entregado', compact('cotizaciones'));
    }


    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('cotizaciones.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $userId = auth('web')->id();

        // Validar los datos del formulario
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'productos' => 'required|array',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.subtotal' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
            'descuento' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calcular el total con descuento (si existe)
        $total = array_sum(array_column($request->productos, 'subtotal'));
        $descuento = $request->descuento ?? 0; // Si no hay descuento, se asigna 0
        $totalConDescuento = $total - ($total * ($descuento / 100));

        // Crear la cotización
        $cotizacion = Cotizacion::create([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'total' => $totalConDescuento,
            'user_id' => $userId, // Asociar al usuario logueado
            'estado' => 1, // Estado inicial: Pendiente de aprobación
            'observaciones' => $request->observaciones,
            'descuento' => $descuento,
        ]);

        // Crear los detalles de la cotización
        foreach ($request->productos as $producto_id => $producto) {
            $cotizacion->detalles()->create([
                'producto_id' => $producto_id,
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio'],
                'subtotal' => $producto['subtotal'],
            ]);
        }

        // Redirigir al índice con un mensaje de éxito
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
            'estado' => 'required|integer|between:1,5', // Estado: valores entre 1 y 5
            'observaciones' => 'nullable|string|max:255',
            'descuento' => 'nullable|numeric|min:0|max:100',
        ]);

        // Obtener la fecha, si no viene del formulario usa la fecha actual
        $fecha = $request->input('fecha') ?? Carbon::now()->toDateString();

        // Encontrar la cotización
        $cotizacion = Cotizacion::findOrFail($id);

        // Calcular el total con descuento si existe
        $total = collect($request->productos)->sum('subtotal');
        $descuento = $request->input('descuento', 0); // Valor por defecto 0
        $totalConDescuento = $total - ($total * ($descuento / 100));

        // Verificar si el estado cambió a "Entregado y Cobrado"
        $estadoAnterior = $cotizacion->estado;

        // Actualizar los datos de la cotización
        $cotizacion->update([
            'cliente_id' => $request->cliente_id,
            'fecha' => $fecha,
            'total' => $totalConDescuento,
            'estado' => $request->estado, // Actualizar el estado
            'observaciones' => $request->observaciones,
            'descuento' => $descuento,
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

        // Si el estado cambió a "Entregado y Cobrado", actualizar el stock de los productos
        if ($estadoAnterior !== 5 && $request->estado == 5) {
            foreach ($request->productos as $producto) {
                $productoModel = Producto::findOrFail($producto['id']);
                $productoModel->decrement('stock', $producto['cantidad']);
            }
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
