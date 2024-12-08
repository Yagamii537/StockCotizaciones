<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word(),
            'descripcion' => $this->faker->sentence(),
            'precio' => $this->faker->randomFloat(2, 1, 100), // Genera un precio entre 1 y 100
            'stock' => $this->faker->numberBetween(1, 50),   // Genera stock entre 1 y 50
            'categoria_id' => Categoria::factory(),         // Genera una categoría asociada
        ];
    }
}
