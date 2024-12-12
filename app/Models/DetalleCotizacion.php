<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    use HasFactory;

    protected $fillable = ['cotizacion_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal'];

    // Relación con cotización
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
