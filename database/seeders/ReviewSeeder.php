<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::all(); // Obtener todos los usuarios
        $productos = Product::all(); // Obtener todos los productos disponibles

        // Revisar si hay productos disponibles
        if ($productos->count() === 0) {
            $this->command->info('⚠️ No hay productos en la base de datos. Por favor, crea productos primero.');
            return;
        }

        // Crear una reseña por usuario para un producto aleatorio
        foreach ($usuarios as $usuario) {
            Review::create([
                'user_id'    => $usuario->id,
                'product_id' => $productos->random()->id,
                'rating'     => rand(3, 5),
                'title'      => 'Reseña de ' . $usuario->name,
                'comment'    => 'Me gustó mucho este producto. ¡Lo recomiendo!',
            ]);
        }
    }
}
