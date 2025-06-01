<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario principal
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Otros 7 usuarios con nuevos nombres y correos
        $usuarios = [
            ['name' => 'Elena Morales', 'email' => 'elena.morales@example.com'],
            ['name' => 'Diego Castro', 'email' => 'diego.castro@example.com'],
            ['name' => 'Valeria Ruiz', 'email' => 'valeria.ruiz@example.com'],
            ['name' => 'Andrés Navarro', 'email' => 'andres.navarro@example.com'],
            ['name' => 'Camila Ríos', 'email' => 'camila.rios@example.com'],
            ['name' => 'Fernando León', 'email' => 'fernando.leon@example.com'],
            ['name' => 'Natalia Franco', 'email' => 'natalia.franco@example.com'],
        ];

        foreach ($usuarios as $usuario) {
            User::create([
                'name' => $usuario['name'],
                'email' => $usuario['email'],
                'password' => bcrypt('password'),
            ]);
        }
    }
}
