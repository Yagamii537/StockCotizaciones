@extends('adminlte::page')

@section('title', 'Nueva Cotización')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
    <h1>Crear Nueva Cotización</h1>
@stop

@section('content')
    <form action="{{ route('cotizaciones.store') }}" method="POST">
        @csrf

        <!-- Campo de Búsqueda de Cliente -->
        <div class="form-group">
            <label for="cliente_search">Buscar Cliente</label>
            <input type="text" id="cliente_search" class="form-control" placeholder="Escriba el nombre o cédula del cliente">
            <ul id="cliente_suggestions" class="list-group mt-2" style="display: none;"></ul>
            <input type="hidden" name="cliente_id" id="cliente_id">
        </div>

        <!-- Información del Cliente -->
        <div id="cliente_info" style="display: none;">
            <h5>Información del Cliente</h5>
            <p><strong>Cédula:</strong> <span id="cliente_cedula"></span></p>
            <p><strong>Nombres:</strong> <span id="cliente_nombres"></span></p>
            <p><strong>Apellidos:</strong> <span id="cliente_apellidos"></span></p>
            <p><strong>Dirección:</strong> <span id="cliente_direccion"></span></p>
            <p><strong>Teléfono:</strong> <span id="cliente_telefono"></span></p>
        </div>

        <!-- Fecha de la Cotización -->
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label for="producto_search">Buscar Producto</label>
            <input type="text" id="producto_search" class="form-control" placeholder="Escriba el nombre o código del producto">
            <ul id="producto_suggestions" class="list-group mt-2" style="display: none;"></ul>
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
                <!-- Las filas dinámicas se agregarán aquí -->
            </tbody>
        </table>

        <div class="form-group">
            <label for="descuento">Descuento (%)</label>
            <input type="number" step="0.01" name="descuento" id="descuento" class="form-control"
                placeholder="Ingrese el descuento opcional"
                value="{{ old('descuento', $cotizacione->descuento ?? '') }}">
        </div>

        <!-- Total -->
        <div class="form-group text-right">
            <h5><strong>Total:</strong> $<span id="total">0.00</span></h5>
        </div>


        <!-- Observaciones -->
        <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
                    placeholder="Escriba observaciones opcionales">{{ old('observaciones', $cotizacione->observaciones ?? '') }}</textarea>
        </div>

        <!-- Descuento -->





        <!-- Botones de Acción -->
        <div class="form-group">
            <button type="submit" class="btn btn-success">Guardar Cotización</button>
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
        const clienteSearchInput = document.getElementById('cliente_search');
        const clienteSuggestions = document.getElementById('cliente_suggestions');
        const clienteInfo = document.getElementById('cliente_info');
        const clienteIdInput = document.getElementById('cliente_id');

        const clienteCedula = document.getElementById('cliente_cedula');
        const clienteNombres = document.getElementById('cliente_nombres');
        const clienteApellidos = document.getElementById('cliente_apellidos');
        const clienteDireccion = document.getElementById('cliente_direccion');
        const clienteTelefono = document.getElementById('cliente_telefono');

        clienteSearchInput.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length < 2) {
                clienteSuggestions.style.display = 'none';
                return;
            }

            // Realizar una solicitud al backend
            fetch(`/clientes/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    clienteSuggestions.innerHTML = '';
                    if (data.length > 0) {
                        clienteSuggestions.style.display = 'block';
                        data.forEach(cliente => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'list-group-item-action');
                            li.textContent = `${cliente.nombres} ${cliente.apellidos} - ${cliente.cedula}`;
                            li.dataset.id = cliente.id;
                            li.dataset.cedula = cliente.cedula;
                            li.dataset.nombres = cliente.nombres;
                            li.dataset.apellidos = cliente.apellidos;
                            li.dataset.direccion = cliente.direccion;
                            li.dataset.telefono = cliente.telefono;

                            li.addEventListener('click', function () {
                                // Asignar valores al formulario
                                clienteIdInput.value = this.dataset.id;
                                clienteCedula.textContent = this.dataset.cedula;
                                clienteNombres.textContent = this.dataset.nombres;
                                clienteApellidos.textContent = this.dataset.apellidos;
                                clienteDireccion.textContent = this.dataset.direccion;
                                clienteTelefono.textContent = this.dataset.telefono;

                                // Mostrar información del cliente
                                clienteInfo.style.display = 'block';
                                clienteSuggestions.style.display = 'none';
                                clienteSearchInput.value = '';
                            });

                            clienteSuggestions.appendChild(li);
                        });
                    } else {
                        clienteSuggestions.style.display = 'none';
                    }
                });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const productoSearchInput = document.getElementById('producto_search');
    const productoSuggestions = document.getElementById('producto_suggestions');
    const productosTable = document.getElementById('productos_table').querySelector('tbody');
    const totalElement = document.getElementById('total');
    let total = 0;

    // Buscar productos
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
                if (data.length > 0) {
                    productoSuggestions.style.display = 'block';
                    data.forEach(producto => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item', 'list-group-item-action');
                        li.textContent = `${producto.nombre} - $${producto.precio}`;
                        li.dataset.id = producto.id;
                        li.dataset.nombre = producto.nombre;
                        li.dataset.precio = producto.precio;

                        li.addEventListener('click', function () {
                            agregarProducto(this.dataset);
                            productoSuggestions.style.display = 'none';
                            productoSearchInput.value = '';
                        });

                        productoSuggestions.appendChild(li);
                    });
                } else {
                    productoSuggestions.style.display = 'none';
                }
            });
    });

    // Agregar producto a la tabla
    function agregarProducto(data) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${data.nombre}</td>
            <td>
                <input type="number" name="productos[${data.id}][cantidad]" class="form-control cantidad" min="1" value="1">
            </td>
            <td>
                <input type="number" name="productos[${data.id}][precio]" class="form-control precio_unitario" value="${data.precio}" readonly>
            </td>
            <td>
                <input type="number" name="productos[${data.id}][subtotal]" class="form-control subtotal" value="${data.precio}" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button>
            </td>
        `;

        row.querySelector('.cantidad').addEventListener('input', function () {
            actualizarSubtotal(row, data.precio);
        });

        row.querySelector('.remove-row').addEventListener('click', function () {
            productosTable.removeChild(row);
            actualizarTotal();
        });

        productosTable.appendChild(row);
        actualizarTotal();
    }

    // Actualizar subtotal
    function actualizarSubtotal(row, precio) {
        const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
        const subtotal = cantidad * parseFloat(precio);
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        actualizarTotal();
    }

    // Actualizar total
    function actualizarTotal() {
        total = 0;
        document.querySelectorAll('.subtotal').forEach(subtotalInput => {
            total += parseFloat(subtotalInput.value) || 0;
        });
        totalElement.textContent = total.toFixed(2);
    }
});

</script>
@stop
