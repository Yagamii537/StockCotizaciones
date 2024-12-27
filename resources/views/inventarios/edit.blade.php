@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1>Editar Producto</h1>
@stop

@section('content')
    <form action="{{ route('inventarios.update', $inventario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $inventario->nombre }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control">{{ $inventario->descripcion }}</textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ $inventario->precio }}" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ $inventario->stock }}" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-control" required>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $inventario->categoria_id == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="coloresModelos">Colores y Modelos</label>
            <input type="text" name="coloresModelos" id="coloresModelos" class="form-control" value="{{ $inventario->coloresModelos }}">
        </div>

        <div class="form-group">
            <label for="medida">Medida</label>
            <input type="text" name="medida" id="medida" class="form-control" value="{{ $inventario->medida }}">
        </div>

        <div class="form-group">
            <label for="comisionUnidad">Comisión por Unidad</label>
            <input type="number" step="0.01" name="comisionUnidad" id="comisionUnidad" class="form-control" value="{{ $inventario->comisionUnidad }}">
        </div>



        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
@stop
