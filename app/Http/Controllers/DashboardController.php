<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use Carbon\Carbon;
use App\Models\Producto;

class DashboardController extends Controller
{
    public function index()
    {
        // Cotizaciones por día de la semana
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $cotizaciones = Cotizacion::whereBetween('fecha', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(fecha) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $labels = [];
        $data = [];

        foreach ($cotizaciones as $cotizacion) {
            $labels[] = Carbon::parse($cotizacion->fecha)->format('l'); // Día de la semana
            $data[] = $cotizacion->total;
        }

        // Productos con stock bajo (< 10)
        $productosStockBajo = Producto::where('stock', '<', 10)
            ->select('nombre', 'stock')
            ->orderBy('stock', 'asc')
            ->get();

        return view('dashboard', [
            'labels' => $labels,
            'data' => $data,
            'productosStockBajo' => $productosStockBajo
        ]);
    }
}
