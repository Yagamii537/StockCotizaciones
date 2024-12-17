<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA</h1>
        <p><strong>Importadora Sai Green</strong></p>
        <p>Dirección: Sangolquí, Quito</p>
        <p>Teléfono: 0998964594</p>
    </div>

    <p><strong>Cliente:</strong> {{ $cotizacion->cliente->nombres }} {{ $cotizacion->cliente->apellidos }}</p>
    <p><strong>Dirección:</strong> {{ $cotizacion->cliente->direccion }}</p>
    <p><strong>Fecha:</strong> {{ $cotizacion->fecha }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Tarifa</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizacion->detalles as $detalle)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> ${{ number_format($cotizacion->total, 2) }}</p>

    <div class="footer">
        <p>Firma del Responsable</p>
        <p>______________________</p>
    </div>
</body>
</html>
