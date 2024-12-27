@extends('adminlte::page')

@section('title', 'Inventario')
{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop
@section('content_header')
    <h1>Gestión de Inventario</h1>
@stop

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <a href="{{ route('inventarios.create') }}" class="btn btn-primary mb-3">Agregar Producto</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Colores/Modelos</th>
                <th>Medida</th>
                <th>Comisión por Unidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventarios as $inventario)
                <tr>
                    <td>{{ $inventario->id }}</td>
                    <td>{{ $inventario->nombre }}</td>
                    <td>{{ $inventario->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td>${{ $inventario->precio }}</td>
                    <td>{{ $inventario->stock }}</td>
                    <td>{{ $inventario->coloresModelos ?? 'N/A' }}</td>
                    <td>{{ $inventario->medida ?? 'N/A' }}</td>
                    <td>${{ number_format($inventario->comisionUnidad, 2) }}</td>
                    <td>

                        <a href="{{ route('inventarios.edit', $inventario) }}" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('inventarios.destroy', $inventario) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')"><i class="fas fa-trash"></i></button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
