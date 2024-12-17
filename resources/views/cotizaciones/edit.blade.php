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
            <input type="text" id="cliente_search" class="form-control" placeholder="Escriba el nombre o cédula del cliente" value="{{ $cotizacione->cliente->nombres }} {{ $cotizacione->cliente->apellidos }}">
            <ul id="cliente_suggestions" class="list-group mt-2" style="display: none;"></ul>
            <input type="hidden" name="cliente_id" id="cliente_id" value="{{ $cotizacione->cliente_id }}">
        </div>

        <!-- Información del Cliente -->
        <div id="cliente_info">
            <h5>Información del Cliente</h5>
            <p><strong>Cédula:</strong> <span id="cliente_cedula">{{ $cotizacione->cliente->cedula }}</span></p>
            <p><strong>Nombres:</strong> <span id="cliente_nombres">{{ $cotizacione->cliente->nombres }}</span></p>
            <p><strong>Apellidos:</strong> <span id="cliente_apellidos">{{ $cotizacione->cliente->apellidos }}</span></p>
            <p><strong>Dirección:</strong> <span id="cliente_direccion">{{ $cotizacione->cliente->direccion }}</span></p>
            <p><strong>Teléfono:</strong> <span id="cliente_telefono">{{ $cotizacione->cliente->telefono }}</span></p>
        </div>

        <!-- Fecha de la Cotización -->
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $cotizacione->fecha }}" required>
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
                @foreach ($cotizacione->detalles as $detalle)
                    <tr>
                        <td>
                            {{ $detalle->producto->nombre }}
                            <input type="hidden" name="productos[{{ $detalle->producto_id }}][id]" value="{{ $detalle->producto_id }}">
                        </td>
                        <td>
                            <input type="number" name="productos[{{ $detalle->producto_id }}][cantidad]" class="form-control cantidad" min="1" value="{{ $detalle->cantidad }}">
                        </td>
                        <td>
                            <input type="number" name="productos[{{ $detalle->producto_id }}][precio]" class="form-control precio_unitario" value="{{ $detalle->precio_unitario }}" readonly>
                        </td>
                        <td>
                            <input type="number" name="productos[{{ $detalle->producto_id }}][subtotal]" class="form-control subtotal" value="{{ $detalle->subtotal }}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        <div class="form-group text-right">
            <h5><strong>Total:</strong> $<span id="total">{{ number_format($cotizacione->total, 2) }}</span></h5>
        </div>

        <!-- Botones -->
        <div class="form-group">
            <button type="submit" class="btn btn-success">Actualizar Cotización</button>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const clienteSearchInput = document.getElementById('cliente_search');
        const clienteSuggestions = document.getElementById('cliente_suggestions');
        const clienteIdInput = document.getElementById('cliente_id');
        const clienteCedula = document.getElementById('cliente_cedula');
        const clienteNombres = document.getElementById('cliente_nombres');
        const clienteApellidos = document.getElementById('cliente_apellidos');
        const clienteDireccion = document.getElementById('cliente_direccion');
        const clienteTelefono = document.getElementById('cliente_telefono');

        // Buscar cliente
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
                                clienteIdInput.value = this.dataset.id;
                                clienteCedula.textContent = this.dataset.cedula;
                                clienteNombres.textContent = this.dataset.nombres;
                                clienteApellidos.textContent = this.dataset.apellidos;
                                clienteDireccion.textContent = this.dataset.direccion;
                                clienteTelefono.textContent = this.dataset.telefono;

                                clienteSuggestions.style.display = 'none';
                                clienteSearchInput.value = `${this.dataset.nombres} ${this.dataset.apellidos}`;
                            });

                            clienteSuggestions.appendChild(li);
                        });
                    } else {
                        clienteSuggestions.style.display = 'none';
                    }
                });
        });

        // Funciones existentes para productos
        const productoSearchInput = document.getElementById('producto_search');
        const productoSuggestions = document.getElementById('producto_suggestions');
        const productosTable = document.getElementById('productos_table').querySelector('tbody');
        const totalElement = document.getElementById('total');
        let total = 0;

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

        function actualizarSubtotal(row, precio) {
            const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
            const subtotal = cantidad * parseFloat(precio);
            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            actualizarTotal();
        }

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
