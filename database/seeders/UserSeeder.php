<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'username' => 'admin', // 🔹 Agrega un valor para 'username'
            'email' => 'admin@placapp.com',
            'password' => Hash::make('password'),
            'role' => 1,
        ]);

        // Crear más usuarios de prueba con Factory (opcional)
        User::factory(10)->create();
    }
}
