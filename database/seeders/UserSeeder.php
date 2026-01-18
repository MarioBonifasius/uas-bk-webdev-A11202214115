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
        $user = [
            [
                'nama' => 'Admin User',
                'email' => 'Admin@gmail.com',
                'password' => bcrypt('admin123'),
                'no_telp' => '+628312253030',
                'role' => 'admin',
            ],
            [
                'nama' => 'UserCoyy',
                'email' => 'Admin1@gmail.com',
                'password' => bcrypt('user123'),
                'no_telp' => '+628312254444',
                'role' => 'user',
            ]
        ];
        foreach ($user as $user) {
            User::create($user);
        }
    }
}
