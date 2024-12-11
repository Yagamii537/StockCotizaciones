@extends('adminlte::page')

@section('title', 'Crear Cliente')

@section('content_header')
    <h1>Crear Cliente</h1>
@stop

@section('content')
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea name="direccion" id="direccion" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@stop
