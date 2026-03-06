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
            'religion' => $this->faker->randomElement(['Catholic', 'Christian', 'Born Again', 'Seventh Day Adventist', 'Others']),
            'citizenship' => 'Filipino',
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widow/Widower', 'Separated', 'Legally Separated']),
            'contact_number' => $this->faker->numerify('09#########'),
            'email' => $this->faker->unique()->safeEmail(),
            'ownership' => $this->faker->randomElement(['Owned', 'Co-Owner', 'Tenant', 'Co-Tenant', 'Living with the owner', 'Living with the tenant']),
            'registered_voter' => $this->faker->randomElement(['Yes - within', 'Yes - elsewhere', 'No']),
            'precinct_number' => $this->faker->optional()->numerify('###A'),
            'household_id' => Household::factory(),
            'residence_since' => $this->faker->numberBetween(1990, now()->year),
            'role' => $this->faker->randomElement(['Spouse', 'Child', 'Relative']),
            'educational_attainment' => $this->faker->randomElement([
                'Elementary',
                'High School',
                'Senior High',
                'College',
                'Vocational',
                'Post Graduate'
            ]),
            'occupation' => $this->faker->jobTitle(),
            'employment_status' => $this->faker->randomElement([
                'Employment Full-time', 'Employment Part-time', 'Self-Employed', 'Unemployed', 
                'Student', 'Out of School Children', 'Out of School Youth',
                'Retired', 'Homemaker', 'Others']),
            'monthly_income' => $this->faker->randomElement([
                'Less than 12,000', '12,001 to 24,000', '24,001 to 48,000', '48,001 to 84,000', '84,001 to 145,000', '145,001 to 240,000', '240,001 and above'
            ]),
        ];
    }
}
