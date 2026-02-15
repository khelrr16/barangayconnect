<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Household>
 */
class HouseholdFactory extends Factory
{

    public function definition(): array
    {
        return [
            'block' => $this->faker->numberBetween(1, 20),
            'lot' => $this->faker->numberBetween(1, 50),
            'unit' => $this->faker->optional()->numberBetween(1, 10),
            'street' => $this->faker->streetName(),
            'barangay' => $this->faker->randomElement([
                'Conpil I Village', 
                'Conpil III Executive', 
                'Console 1 Village', 
                'Greatland Village',
                'Guevara Subdivision',
                'Pacita 2A',
                'Pacita 2B',
                ]),
            'city' => 'San Pedro City',
            'province' => 'Laguna',
            'pet_count' => $this->faker->numberBetween(0, 5),
        ];
    }
}
