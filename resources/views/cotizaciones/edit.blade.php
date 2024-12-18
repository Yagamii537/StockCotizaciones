@extends('adminlte::page')

@section('title', 'Editar Cotización')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
    <h1>Editar Cotización</h1>
@stop

@section('content')
    <form action="{{ route('cotizaciones.update', $cotizacione->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo de Búsqueda de Cliente -->
        <div class="form-group">
            <label for="cliente_search">Buscar Cliente</label>
            <input type="text" id="cliente_search" class="form-control" placeholder="Escriba el nombre o cédula del cliente"
                   value="{{ $cotizacione->cliente->nombres }} {{ $cotizacione->cliente->apellidos }}">
            <ul id="cliente_suggestions" class="list-group mt-2" style="display: none;"></ul>
            <input type="hidden" name="cliente_id" id="cliente_id" value="{{ $cotizacione->cliente_id }}">
        </div>

        <!-- Información del Cliente -->
        <div id="cliente_info" style="display: block;">
            <h5>Información del Cliente</h5>
            <p><strong>Cédula:</strong> <span id="cliente_cedula">{{ $cotizacione->cliente->cedula }}</span></p>
            <p><strong>Nombres:</strong> <span id="cliente_nombres">{{ $cotizacione->cliente->nombres }}</span></p>
            <p><strong>Apellidos:</strong> <span id="cliente_apellidos">{{ $cotizacione->cliente->apellidos }}</span></p>
            <p><strong>Dirección:</strong> <span id="cliente_direccion">{{ $cotizacione->cliente->direccion }}</span></p>
            <p><strong>Teléfono:</strong> <span id="cliente_telefono">{{ $cotizacione->cliente->telefono }}</span></p>
        </div>

        <!-- Buscar Producto -->
        <div class="form-group">
            <label for="producto_search">Buscar Producto</label>
            <input type="text" id="producto_search" class="form-control" placeholder="Escriba el nombre o código del producto">
            <ul id="producto_suggestions" class="list-group mt-2" style="display: none;"></ul>
        </div>

        <!-- Tabla de Productos -->
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
                @foreach ($cotizacione->detalles as $index => $detalle)
                <tr>
                    <td>
                        {{ $detalle->producto->nombre }}
                        <input type="hidden" name="productos[{{ $index }}][id]" value="{{ $detalle->producto_id }}">
                    </td>
                    <td>
                        <input type="number" name="productos[{{ $index }}][cantidad]" class="form-control cantidad" min="1" value="{{ $detalle->cantidad }}">
                    </td>
                    <td>
                        <input type="number" name="productos[{{ $index }}][precio]" class="form-control precio_unitario" value="{{ $detalle->precio_unitario }}" readonly>
                    </td>
                    <td>
                        <input type="number" name="productos[{{ $index }}][subtotal]" class="form-control subtotal" value="{{ $detalle->subtotal }}" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <!-- Descuento -->
        <div class="form-group">
            <label for="descuento">Descuento (%)</label>
            <input type="number" step="0.01" name="descuento" id="descuento" class="form-control"
                placeholder="Ingrese el descuento opcional"
                value="{{ old('descuento', $cotizacione->descuento ?? '') }}">
        </div>

        <!-- Total -->
        <div class="form-group text-right">
            <h5><strong>Total:</strong> $<span id="total">{{ number_format($cotizacione->total, 2) }}</span></h5>
        </div>
        <!-- Estado -->
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="1" {{ isset($cotizacione) && $cotizacione->estado == 1 ? 'selected' : '' }}>Pendiente de aprobación</option>
                <option value="2" {{ isset($cotizacione) && $cotizacione->estado == 2 ? 'selected' : '' }}>Pendiente de pago</option>
                <option value="3" {{ isset($cotizacione) && $cotizacione->estado == 3 ? 'selected' : '' }}>Diferido</option>
                <option value="4" {{ isset($cotizacione) && $cotizacione->estado == 4 ? 'selected' : '' }}>Cobrado pendiente de entrega</option>
                <option value="5" {{ isset($cotizacione) && $cotizacione->estado == 5 ? 'selected' : '' }}>Entregado y cobrado</option>
            </select>
        </div>

        <!-- Observaciones -->
        <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
                    placeholder="Escriba observaciones opcionales">{{ old('observaciones', $cotizacione->observaciones ?? '') }}</textarea>
        </div>




        <div class="form-group">
            <button type="submit" class="btn btn-success">Actualizar Cotización</button>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@stop

@section('js')
<script>
    // Escuchar cambios en el descuento
document.getElementById('descuento').addEventListener('input', actualizarTotal);

// Actualizar Total con descuento
function actualizarTotal() {
    let total = 0;

    document.querySelectorAll('.subtotal').forEach(el => {
        total += parseFloat(el.value) || 0;
    });

    const descuentoInput = document.getElementById('descuento');
    const descuento = parseFloat(descuentoInput.value) || 0;

    // Aplicar descuento (si existe)
    const totalConDescuento = total - (total * descuento / 100);
    document.getElementById('total').textContent = totalConDescuento.toFixed(2);
}

    document.addEventListener('DOMContentLoaded', function () {
        // === Variables ===
        const clienteSearchInput = document.getElementById('cliente_search');
        const clienteSuggestions = document.getElementById('cliente_suggestions');
        const clienteIdInput = document.getElementById('cliente_id');
        const clienteCedula = document.getElementById('cliente_cedula');
        const clienteNombres = document.getElementById('cliente_nombres');
        const clienteApellidos = document.getElementById('cliente_apellidos');
        const clienteDireccion = document.getElementById('cliente_direccion');
        const clienteTelefono = document.getElementById('cliente_telefono');

        const productoSearchInput = document.getElementById('producto_search');
        const productoSuggestions = document.getElementById('producto_suggestions');
        const productosTable = document.querySelector('#productos_table tbody');
        const totalElement = document.getElementById('total');

        // === BUSCAR CLIENTE ===
        clienteSearchInput.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length < 2) {
                clienteSuggestions.style.display = 'none';
                return;
            }

            fetch(`/clientes/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    clienteSuggestions.innerHTML = '';
                    clienteSuggestions.style.display = 'block';
                    data.forEach(cliente => {
                        const li = document.createElement('li');
                        li.textContent = `${cliente.nombres} ${cliente.apellidos}`;
                        li.classList.add('list-group-item');
                        li.addEventListener('click', function () {
                            clienteIdInput.value = cliente.id;
                            clienteCedula.textContent = cliente.cedula;
                            clienteNombres.textContent = cliente.nombres;
                            clienteApellidos.textContent = cliente.apellidos;
                            clienteDireccion.textContent = cliente.direccion;
                            clienteTelefono.textContent = cliente.telefono;

                            clienteSuggestions.style.display = 'none';
                            clienteSearchInput.value = `${cliente.nombres} ${cliente.apellidos}`;
                        });
                        clienteSuggestions.appendChild(li);
                    });
                });
        });

        // === BUSCAR PRODUCTO ===
        productoSearchInput.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length < 2) {
                productoSuggestions.style.display = 'none';
                return;
            }

            fetch(`/inventarios/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    productoSuggestions.innerHTML = '';
                    productoSuggestions.style.display = 'block';
                    data.forEach(producto => {
                        const li = document.createElement('li');
                        li.textContent = producto.nombre;
                        li.classList.add('list-group-item');
                        li.addEventListener('click', function () {
                            agregarProducto(producto);
                            productoSuggestions.style.display = 'none';
                            productoSearchInput.value = '';
                        });
                        productoSuggestions.appendChild(li);
                    });
                });
        });

        // === AGREGAR PRODUCTO ===
        function agregarProducto(producto) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${producto.nombre}<input type="hidden" name="productos[${producto.id}][id]" value="${producto.id}"></td>
                <td><input type="number" class="form-control cantidad" name="productos[${producto.id}][cantidad]" min="1" value="1"></td>
                <td><input type="number" class="form-control" name="productos[${producto.id}][precio]" value="${producto.precio}" readonly></td>
                <td><input type="number" class="form-control subtotal" name="productos[${producto.id}][subtotal]" value="${producto.precio}" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button></td>
            `;

            row.querySelector('.cantidad').addEventListener('input', function () {
                actualizarSubtotal(row, producto.precio);
            });

            row.querySelector('.remove-row').addEventListener('click', function () {
                row.remove();
                actualizarTotal();
            });

            productosTable.appendChild(row);
            actualizarTotal();
        }

        // === EVENTOS PARA FILAS PRE-CARGADAS ===
        asignarEventosFilasExistentes();

        function asignarEventosFilasExistentes() {
            document.querySelectorAll('.cantidad').forEach(input => {
                input.addEventListener('input', function () {
                    const row = input.closest('tr');
                    const precio = parseFloat(row.querySelector('[name$="[precio]"]').value) || 0;
                    actualizarSubtotal(row, precio);
                });
            });

            document.querySelectorAll('.remove-row').forEach(button => {
                button.addEventListener('click', function () {
                    button.closest('tr').remove();
                    actualizarTotal();
                });
            });
        }

        // === ACTUALIZAR SUBTOTAL Y TOTAL ===
        function actualizarSubtotal(row, precio) {
            const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
            const subtotal = cantidad * precio;
            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            actualizarTotal();
        }

        function actualizarTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(el => {
                total += parseFloat(el.value) || 0;
            });
            totalElement.textContent = total.toFixed(2);
        }
    });
</script>
@stop

