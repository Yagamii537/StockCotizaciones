@extends('adminlte::page')

@section('title', 'Reporte de Productos Vendidos')

@section('content_header')
    <h1>Reporte de Productos Vendidos</h1>
@stop

@section('content')
@if(!isset($ventas))
    <form action="{{ route('reportes.productos.filtrar') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="producto_id">Producto</label>
                <select name="producto_id" id="producto_id" class="form-control" required>
                    <option value="">Seleccione un producto</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="fecha_inicio">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="fecha_fin">Fecha Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
            </div>
        </div>
    </form>
@else
    <h4>Reporte de: {{ $producto->nombre }}</h4>
    <p>Desde: {{ $fechaInicio }} - Hasta: {{ $fechaFin }}</p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $venta->fecha }}</td>
                    <td>{{ $venta->total_vendido }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No se encontraron ventas en el rango seleccionado.</td>
                </tr>
            @endforelse
            @if ($ventas->isNotEmpty())
                <tr>
                    <td><strong>Total General</strong></td>
                    <td><strong>{{ $totalGeneral }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
@endif
@stop
