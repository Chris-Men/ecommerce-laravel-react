<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            'tecnología',
            'seguridad',
            'electrodomésticos',
            'salud',
            'deporte',
            'belleza',
            'juguetes',
            'oficina',
            'mascotas',
            'jardin',
            'libros',
            'alimentos',
        ];

        foreach ($categorias as $nombre) {
            $slug = Str::slug($nombre);
            $imagenDemo = public_path("images-demo/categories/{$slug}.jpg");
            $rutaFinal = "categories/{$slug}.jpg";

            // Copiar imagen desde public/ a storage/app/public/categories
            if (file_exists($imagenDemo)) {
                Storage::disk('public')->put($rutaFinal, file_get_contents($imagenDemo));
            }

            Category::create([
                'name' => ucfirst($nombre),
                'slug' => $slug,

                'image' => $rutaFinal,
            ]);
        }
    }
}
