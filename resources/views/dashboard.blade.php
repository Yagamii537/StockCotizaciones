@extends('adminlte::page')

@section('title', 'Dashboard Cotizaciones')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <!-- Gráfico de cotizaciones -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total de Cotizaciones por Día</h3>
                </div>
                <div class="card-body">
                    <canvas id="cotizacionesChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabla de productos con stock bajo -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Productos con Stock Bajo</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productosStockBajo as $producto)
                                <tr>
                                    <td>{{ $producto->nombre }}</td>
                                    <td>{{ $producto->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No hay productos con stock bajo</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('cotizacionesChart').getContext('2d');
    const cotizacionesChart = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico
        data: {
            labels: @json($labels), // Días de la semana
            datasets: [{
                label: 'Cotizaciones',
                data: @json($data), // Totales de cotizaciones
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@stop
