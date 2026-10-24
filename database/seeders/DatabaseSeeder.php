<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = [
            'name' => 'Administrator',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ];
        $customer = [
            'name' => 'Customer',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ];

        if (Schema::hasColumn('users', 'username')) {
            $admin['username'] = 'admin';
            $customer['username'] = 'customer';
        }

        User::updateOrCreate(['email' => 'admin@example.com'], $admin);
        User::updateOrCreate(['email' => 'customer@example.com'], $customer);
    }
}
