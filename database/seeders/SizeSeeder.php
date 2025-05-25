<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $tallas = ['S', 'M', 'L', 'XL', 'XXL'];

        foreach ($tallas as $talla) {
            Size::create([
                'name' => $talla,
                'slug' => Str::slug($talla), // genera el slug a partir del nombre
            ]);
        }
    }
}
