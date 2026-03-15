<?php

namespace Database\Factories;

use App\Models\Admin\Program;
use App\Models\Admin\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\ResidentProgram>
 */
class ResidentProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resident_id' => Resident::factory(),
            'program_id' => $this->faker->numberBetween(1, 4),
            // 'batch_year' => $this->faker->year(),
            // 'date_received' => $this->faker->date(),
            // 'amount_received' => $this->faker->randomNumber(4),
            'status' => $this->faker->randomElement(['active', 'completed', 'dropped']),
            'remarks' => $this->faker->sentence(),
        ];
    }
}
