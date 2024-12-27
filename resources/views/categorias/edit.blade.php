@extends('adminlte::page')

@section('title', 'Editar Categoría')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
    <h1>Editar Categoría</h1>
@stop

@section('content')
    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $categoria->nombre }}" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control">{{ $categoria->descripcion }}</textarea>
        </div>
        <div class="form-group">
            <label for="seCobraPor">¿Cómo se cobra por?</label>
            <input type="text" name="seCobraPor" id="seCobraPor" class="form-control"
                   value="{{ old('seCobraPor', $categoria->seCobraPor ?? '') }}" placeholder="Ejemplo: Por unidad, Por peso">
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
@stop
