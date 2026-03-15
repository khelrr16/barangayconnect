<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Committee;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        $committees = [
            [
                'name' => 'Budget & Finance',
                'slug' => 'budget_finance',
            ],
            [
                'name' => 'Woman & Family',
                'slug' => 'woman_family',
            ],
            [
                'name' => 'Health & Sanitation',
                'slug' => 'health_sanitation',
            ],
            [
                'name' => 'Environment',
                'slug' => 'environment',
            ],
            [
                'name' => 'Infrastructure',
                'slug' => 'infrastructure',
            ],
            [
                'name' => 'BDRRM',
                'slug' => 'bdrrm',
            ],
            [
                'name' => 'Peace & Order',
                'slug' => 'peace_order',
            ],
            [
                'name' => 'Youth & Sports',
                'slug' => 'youth_sports',
            ],

        ];

        foreach($committees as $committee){
            Committee::create([
                'name' => $committee['name'],
                'slug' => $committee['slug']
            ]);
        }
    }
}
