<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Arzavo\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {

        User::updateOrCreate(
            ['email' => 'monisrazakhan2001@gmail.com'],
            [
                'fname' => 'Monis Raza',
                'lname' => 'Khan',
                'username' => 'monisrazakhan',
                'password' => Hash::make('admin@78692'),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
            ]
        );
    }
}