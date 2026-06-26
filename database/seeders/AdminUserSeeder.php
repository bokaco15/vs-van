<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Jedini nalog — admin. (Registracija je onemogućena.)
        User::updateOrCreate(
            ['email' => 'admin@vstim.rs'],
            [
                'name' => 'VS Tim Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
    }
}
