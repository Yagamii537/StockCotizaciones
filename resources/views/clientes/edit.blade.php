@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
    <h1>Editar Cliente</h1>
@stop

@section('content')
    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" class="form-control" value="{{ old('cedula', $cliente->cedula) }}" required>
        </div>

        <div class="form-group">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres', $cliente->nombres) }}" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos', $cliente->apellidos) }}" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea name="direccion" id="direccion" class="form-control">{{ old('direccion', $cliente->direccion) }}</textarea>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $cliente->email) }}">
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
