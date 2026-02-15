<?php

namespace Database\Seeders;

use App\Models\HealthProfile;
use App\Models\Household;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Household::factory()->count(5)
            ->create()
            ->each(function($household){
                Resident::factory()
                    ->count(rand(1, 3))
                    ->create(['household_id' => $household->id])
                    ->each(function($resident){
                        HealthProfile::factory()
                            ->count(rand(0, 3))
                            ->create(['resident_id' => $resident->id]);
                    });
            });
    }
}
