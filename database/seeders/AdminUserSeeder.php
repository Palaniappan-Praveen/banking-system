<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@bank.com',
            'password' => Hash::make('admin123'),
            'phone' => '1234567890',
            'role' => 'admin',
        ]);
    }
}
