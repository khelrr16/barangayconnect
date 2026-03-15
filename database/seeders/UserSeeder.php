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
            'name' => 'Hitler',
            'official_id' => 1,
            'email' => 'hitler@gmail.com',
            'password' => 'hitler123',
        ]);

        $user1->assignRole('committee_head');
    }
}
