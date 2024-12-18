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
                <th>Estado</th> <!-- Nueva columna -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Mapeo de estados
                $estados = [
                    1 => 'Pendiente de aprobación',
                    2 => 'Pendiente de pago',
                    3 => 'Diferido',
                    4 => 'Cobrado pendiente de entrega',
                    5 => 'Entregado y cobrado',
                ];
            @endphp
            @foreach ($cotizaciones as $cotizacione)
                <tr>
                    <td>{{ $cotizacione->id }}</td>
                    <td>{{ $cotizacione->cliente->nombres }} {{ $cotizacione->cliente->apellidos }}</td>
                    <td>{{ $cotizacione->fecha }}</td>
                    <td>${{ number_format($cotizacione->total, 2) }}</td>
                    <td>
                        <span class="badge badge-info">
                            {{ $estados[$cotizacione->estado] ?? 'Desconocido' }}
                        </span>
                    </td>
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
