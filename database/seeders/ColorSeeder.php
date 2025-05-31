<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colores = ['Rojo', 'Azul', 'Verde', 'Negro', 'Blanco', 'Marron' , 'Celeste' , 'Rosado' , ' Gris' , 'Celeste','.'];

        foreach ($colores as $color) {
        Color::create([
            'name' => $color,
            'slug' => \Illuminate\Support\Str::slug($color),
        ]);
    }
    }
}
