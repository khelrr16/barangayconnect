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
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $staff = User::factory()->create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => 'staff123',
        ]);

        $admin->assignRole('admin');
        $staff->assignRole('staff');
    }
}
