@extends('adminlte::page')

@section('title', 'Nueva Cotización')

@section('content_header')
    <h1>Crear Nueva Cotización</h1>
@stop

@section('content')
    <form action="{{ route('cotizaciones.store') }}" method="POST">
        @csrf

        <!-- Selección de Cliente -->
        <div class="form-group">
            <label for="cliente_id">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-control" required>
                <option value="">Seleccione un cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }}</option>
                @endforeach
            </select>
        </div>

        <!-- Fecha de la Cotización -->
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Tabla para Agregar Productos -->
        <h5>Productos</h5>
        <table class="table table-bordered" id="productos_table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="productos[0][id]" class="form-control" required>
                            <option value="">Seleccione un producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="productos[0][cantidad]" class="form-control cantidad" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" name="productos[0][precio]" class="form-control precio_unitario" step="0.01" value="0.00" readonly>
                    </td>
                    <td>
                        <input type="number" name="productos[0][subtotal]" class="form-control subtotal" step="0.01" value="0.00" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary mb-3" id="add_row">Agregar Producto</button>

        <!-- Botones de Acción -->
        <div class="form-group">
            <button type="submit" class="btn btn-success">Guardar Cotización</button>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@stop

@section('js')
<script>
    let rowIndex = 1;

    // Agregar una nueva fila a la tabla
    $('#add_row').click(function() {
        $('#productos_table tbody').append(`
            <tr>
                <td>
                    <select name="productos[${rowIndex}][id]" class="form-control" required>
                        <option value="">Seleccione un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="productos[${rowIndex}][cantidad]" class="form-control cantidad" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="productos[${rowIndex}][precio]" class="form-control precio_unitario" step="0.01" value="0.00" readonly>
                </td>
                <td>
                    <input type="number" name="productos[${rowIndex}][subtotal]" class="form-control subtotal" step="0.01" value="0.00" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button>
                </td>
            </tr>
        `);
        rowIndex++;
    });

    // Eliminar una fila de la tabla
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        calcularTotal();
    });

    // Calcular Subtotal y Total
    $(document).on('change', 'select[name^="productos"]', function() {
        const precio = $(this).find(':selected').data('precio') || 0;
        const row = $(this).closest('tr');
        row.find('.precio_unitario').val(precio);
        calcularSubtotal(row);
    });

    $(document).on('input', '.cantidad', function() {
        const row = $(this).closest('tr');
        calcularSubtotal(row);
    });

    function calcularSubtotal(row) {
        const cantidad = parseFloat(row.find('.cantidad').val()) || 0;
        const precio = parseFloat(row.find('.precio_unitario').val()) || 0;
        const subtotal = cantidad * precio;
        row.find('.subtotal').val(subtotal.toFixed(2));
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        // Aquí puedes actualizar un campo de total si lo necesitas
    }
</script>
@stop
