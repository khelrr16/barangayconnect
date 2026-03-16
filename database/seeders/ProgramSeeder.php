<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Program::create([
            'name' => '4Ps',
            'description' => 'Pantawid Pamilyang Pilipino Program (4Ps) is a government initiative that provides conditional cash transfers to low-income families in the Philippines. The program aims to improve the health, education, and overall well-being of children and their families by providing financial assistance and promoting access to essential services.',
            'agency' => 'Department of Social Welfare and Development (DSWD)',
            'is_active' => true
        ]);

        Program::create([
            'name' => 'AKAP',
            'description' => 'AKAP (Ating Kanya Ayaw Pumalit) is a government program that provides assistance to families in need.',
            'agency' => 'Department of Social Welfare and Development (DSWD)',
            'is_active' => true
        ]);

        Program::create([
            'name' => 'AICS',
            'description' => 'AICS (Ating Kanya Isang Kita) is a government program that provides assistance to families in need.',
            'agency' => 'Department of Social Welfare and Development (DSWD)',
            'is_active' => true
        ]);

        Program::create([
            'name' => 'TUPAD',
            'description' => 'TUPAD (Tulong sa Pamilya at Dapat) is a government program that provides assistance to families in need.',
            'agency' => 'Department of Labor and Employment (DOLE)',
            'is_active' => true
        ]);
    }
}
