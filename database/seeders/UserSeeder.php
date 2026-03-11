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
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $admin->assignRole('admin');

        $user1 = User::create([
            'name' => 'Ada Wong',
            'official_id' => 1,
            'email' => 'adawong@gmail.com',
            'password' => 'adawong123',
        ]);

        $user1->assignRole('committee_head');
    }
}
