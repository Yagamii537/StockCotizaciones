<?php

namespace App\Http\Controllers;

use App\Models\DetalleCotizacion;
use App\Models\Producto;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('reportes.productos', compact('productos'));
    }

    public function filtrar(Request $request)
    {


        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);


        $productoId = $request->producto_id;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;



        // Obtener los detalles de cotización para el rango de fechas y producto seleccionado
        $ventas = DetalleCotizacion::where('producto_id', $productoId)
            ->whereHas('cotizacion', function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->where('estado', 5); // Solo cotizaciones entregadas y cobradas
            })
            ->selectRaw('DATE(cotizacions.fecha) as fecha, SUM(cantidad) as total_vendido')
            ->join('cotizacions', 'detalle_cotizacions.cotizacion_id', '=', 'cotizacions.id')
            ->groupBy('fecha')
            ->get();

        // Calcular el total general vendido en el rango de fechas
        $totalGeneral = $ventas->sum('total_vendido');


        $producto = Producto::find($productoId);




        return view('reportes.productos', compact('ventas', 'producto', 'fechaInicio', 'fechaFin', 'totalGeneral'));
    }
}
