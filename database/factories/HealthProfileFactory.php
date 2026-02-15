<?php

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthProfile>
 */
class HealthProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'resident_id' => Resident::factory(),
            'health_condition' => $this->faker->randomElement([
                'Hypertension', 'Diabetes', 'Asthma',
                'Heart Disease', 'Chronic Kidney Disease',
                'Cancer', 'Tuberculosis', 'Pregnant'
            ]),
        ];
    }
}
