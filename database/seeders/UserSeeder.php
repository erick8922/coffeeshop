<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@coffeeshop.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '09123456789',
            'address'  => 'Davao City',
        ]);

        User::create([
            'name'     => 'Juan dela Cruz',
            'email'    => 'customer@coffeeshop.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'phone'    => '09987654321',
            'address'  => 'Davao City',
        ]);
    }
}