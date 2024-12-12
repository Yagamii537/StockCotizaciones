<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'fecha', 'total'];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con detalle de cotizaciones
    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class);
    }
}
