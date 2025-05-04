<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $SuperAdmin = User::create(attributes:[
            'name' => 'User',
            'email' => 'User@example.com',
            'password' => bcrypt('password'),
        ]);

    }
}
