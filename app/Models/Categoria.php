<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'seCobraPor',
    ];

    /**
     * Relación con los productos (uno a muchos).
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
