<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word(), // Nombre aleatorio único
            'descripcion' => $this->faker->sentence(),  // Descripción aleatoria
        ];
    }
}
