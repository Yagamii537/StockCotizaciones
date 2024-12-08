<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        // Generar 50 productos con categorías asociadas
        Producto::factory()->count(50)->create();
    }
}
