<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            // Tecnología
            'Samsung', 'Apple', 'Sony', 'Huawei',

            // Seguridad
            'Hikvision', 'Dahua', 'Ring', 'TP-Link',

            // Electrodomésticos
            'LG', 'Whirlpool', 'Bosch', 'Mabe',

            // Salud
            'Omron', 'Ensure', 'Vicks', 'GNC',

            // Deporte
            'Nike', 'Adidas', 'Puma', 'Reebok',

            // Belleza
            'L\'Oréal', 'Maybelline', 'Nivea', 'Dove',

            // Juguetes
            'Lego', 'Mattel', 'Hasbro', 'Fisher-Price',

            // Oficina
            'HP', 'Canon', 'Epson', 'Logitech',

            // Mascotas
            'Pedigree', 'Purina', 'Whiskas', 'Hill\'s',

            // Jardín
            'Truper', 'Fiskars', 'Black+Decker', 'Scotts',

            // Libros
            'Penguin', 'Planeta', 'HarperCollins', 'Anagrama',

            // Alimentos
            'Nestlé', 'Kellogg\'s', 'La Costeña', 'Bimbo'
        ];

        foreach ($marcas as $marca) {
            Brand::create([
                'name' => $marca,
                'slug' => Str::slug($marca),
            ]);
        }
    }
}
