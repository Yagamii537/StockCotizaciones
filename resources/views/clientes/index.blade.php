@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>Gestión de Clientes</h1>
@stop

@section('content')
    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Agregar Cliente</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->cedula }}</td>
                    <td>{{ $cliente->nombres }}</td>
                    <td>{{ $cliente->apellidos }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-success">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
