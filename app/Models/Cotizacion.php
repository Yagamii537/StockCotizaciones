<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cliente_id',
        'fecha',
        'total',
        'estado',
        'observaciones',
        'descuento',
    ];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
