<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cedula',
        'nombres',
        'apellidos',
        'direccion',
        'telefono',
        'email',
        'fecha_registro',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
