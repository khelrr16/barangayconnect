<?php

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommunityOrganization>
 */
class CommunityOrganizationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'resident_id' => Resident::factory(),
            'organization' => $this->faker->randomElement(['LGBTQ+', 'PWD', 'Senior Citizen', 'Solo Parent']),
        ];
    }
}
