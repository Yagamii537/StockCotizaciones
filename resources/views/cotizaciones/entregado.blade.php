@extends('adminlte::page')

@section('title', 'Cotizaciones Entregadas y Cobradas')

@section('content_header')
    <h1>Cotizaciones Entregadas y Cobradas</h1>
@stop

@section('content')
    <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary mb-3">Volver a Cotizaciones</a>

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
                        <a href="{{ route('cotizaciones.pdf', $cotizacione->id) }}" class="btn btn-primary btn-sm">PDF</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
