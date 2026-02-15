<?php

namespace Database\Factories;

use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->lastName(),
            'extension_name' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'III', null]),
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'birthday' => $this->faker->date('Y-m-d', '2005-01-01'),
            'birthplace' => $this->faker->city(),
            'citizenship' => 'Filipino',
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Separated']),
            'contact_number' => $this->faker->numerify('09#########'),
            'email' => $this->faker->unique()->safeEmail(),
            'ownership' => $this->faker->randomElement(['Owned', 'Rented', 'Living with relatives']),
            'registered_voter' => $this->faker->randomElement(['Yes', 'No']),
            'precinct_number' => $this->faker->optional()->numerify('###A'),
            'residence_since' => $this->faker->numberBetween(1990, now()->year),
            'household_id' => Household::factory(),
            'role' => $this->faker->randomElement(['Head', 'Spouse', 'Child', 'Relative']),
            'educational_attainment' => $this->faker->randomElement([
                'Elementary',
                'High School',
                'Senior High',
                'College',
                'Vocational',
                'Post Graduate'
            ]),
            'occupation' => $this->faker->jobTitle(),
            'total_income' => $this->faker->numberBetween(5000, 50000),
            'benificiary_4ps' => $this->faker->randomElement(['Yes', 'No']),
        ];
    }
}
