<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\User; // Importar el modelo User
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'cedula' => $this->faker->unique()->numerify('##########'), // Genera un número de 10 dígitos
            'nombres' => $this->faker->firstName(), // Genera un nombre
            'apellidos' => $this->faker->lastName(), // Genera un apellido
            'direccion' => $this->faker->address(), // Genera una dirección
            'telefono' => $this->faker->numerify('09########'), // Número de teléfono simulado (Ecuador)
            'email' => $this->faker->unique()->safeEmail(), // Genera un email único
            'fecha_registro' => now(), // Fecha de registro actual
        ];
    }
}
