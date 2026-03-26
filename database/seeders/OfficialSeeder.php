<?php

namespace Database\Seeders;

use App\Models\Official;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Official::create([
            'name' => 'Omatsuri Mambo',
            'position' => 'Kagawad',
            'committee_id' => 3,
            'term_start' => now(),
            'term_end' => now(),
        ]);
    }
}
