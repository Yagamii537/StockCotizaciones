@extends('adminlte::page')

@section('title', 'Cotizaciones')

@section('content_header')
    <h1>Gestión de Cotizaciones</h1>
@stop

@section('content')
    <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary mb-3">Nueva Cotización</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->id }}</td>
                    <td>{{ $cotizacion->cliente->nombres }} {{ $cotizacion->cliente->apellidos }}</td>
                    <td>{{ $cotizacion->fecha }}</td>
                    <td>${{ number_format($cotizacion->total, 2) }}</td>
                    <td>
                        <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
