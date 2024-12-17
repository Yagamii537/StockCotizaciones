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
            @foreach ($cotizaciones as $cotizacione)
                <tr>
                    <td>{{ $cotizacione->id }}</td>
                    <td>{{ $cotizacione->cliente->nombres }} {{ $cotizacione->cliente->apellidos }}</td>
                    <td>{{ $cotizacione->fecha }}</td>
                    <td>${{ number_format($cotizacione->total, 2) }}</td>
                    <td>
                        <a href="{{ route('cotizaciones.edit', $cotizacione) }}" class="btn btn-sm btn-success">Editar</a>
                        <form action="{{ route('cotizaciones.destroy', $cotizacione) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                        <a href="{{ route('cotizaciones.pdf', $cotizacione->id) }}" class="btn btn-primary btn-sm">PDF</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
