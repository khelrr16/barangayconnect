<?php

namespace Database\Seeders;

use App\Models\CommunityOrganization;
use App\Models\HealthProfile;
use App\Models\Household;
use App\Models\Resident;
use App\Models\ResidentProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $subs = [
            'Conpil I Village',
            'Conpil III Executive', 
            'Console 1 Village', 
            'Greatland Village',
            'Guevara Subdivision',
            'Pacita 2A',
            'Pacita 2B',
        ];

        foreach($subs as $index => $sub){
            $streets[$index] = fake()->unique()->streetName();
        }

        foreach($subs as $sub){
            for ($b = 1; $b < rand(5,8); $b++){
                for ($l = 1; $l < rand(5,10); $l++){
                    if(rand(1,7) == 7){
                        for($u = 1; $u < rand(3,10); $u++){
                            $household = Household::factory()
                                ->create([
                                    'block' => $b, 
                                    'lot' => $l, 
                                    'unit' => $u, 
                                    'street' => $streets[$b-1],
                                    'subdivision' => $sub
                                ]);

                            $head = Resident::factory()
                                ->create(['household_id' => $household->id, 'role' => 'Head']);

                            $this->attachResidentData($head);

                            Resident::factory()
                                ->count(rand(1, 5))
                                ->create(['household_id' => $household->id])
                                ->each(fn($resident) => $this->attachResidentData($resident));
                        }
                    }
                    else {
                        $household = Household::factory()
                            ->create([
                                'block' => $b, 
                                'lot' => $l, 
                                'street' => $streets[$b-1],
                                'subdivision' => $sub
                            ]);

                        $head = Resident::factory()
                            ->create(['household_id' => $household->id, 'role' => 'Head']);

                        $this->attachResidentData($head);

                        Resident::factory()
                            ->count(rand(1, 5))
                            ->create(['household_id' => $household->id])
                            ->each(fn($resident) => $this->attachResidentData($resident));
                    }
                }
            }
        }
    }

    private function attachResidentData($resident)
    {
        $orgs = [
            'LGBTQ+',
            'PWD',
            'Senior Citizen',
            'Solo Parent'
        ];

        $conditions = [
            'Diabetes',
            'Hypertension',
            'Asthma',
            'Cancer',
            'Heart Disease',
            'Arthritis'
        ];

        $selectedOrgs = collect($orgs)
            ->shuffle()
            ->take(rand(0, 3));

        $selectedConditions = collect($conditions)
            ->shuffle()
            ->take(rand(0, 3));

        foreach ($selectedOrgs as $org) {
            CommunityOrganization::create(['resident_id' => $resident->id, 'organization' => $org]);
        }

        foreach ($selectedConditions as $condition) {
            HealthProfile::create(['resident_id' => $resident->id, 'health_condition' => $condition]);
        }

        if(rand(1, 5) == 1){
            for($p = 1; $p < rand(2, 4); $p++){
                ResidentProgram::factory()
                ->create(['resident_id' => $resident->id, 'program_id' => $p]);
            }
        }
    }
}
