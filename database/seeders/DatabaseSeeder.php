<?php

namespace Database\Seeders;

use App\Models\CommunityOrganization;
use App\Models\HealthProfile;
use App\Models\Household;
use App\Models\Resident;
use App\Models\ResidentProgram;
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
        $this->call([
            PermissionSeeder::class,
            ProgramSeeder::class,
            CommitteeSeeder::class,
            OfficialSeeder::class,
            UserSeeder::class,
            MedicineSeeder::class,
            // ResidentSeeder::class, //BEEEG DATA
        ]);
    }
}
