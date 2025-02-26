<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::all()->random()->id,
            'date' => $this->faker->date(),
            'check_in' => $this->faker->optional(0.9)->time('H:i:s'), // 90% des cas une heure d'arrivée
            'check_out' => $this->faker->optional(0.8)->time('H:i:s'), // 80% des cas une heure de sortie
            'status' => $this->faker->randomElement(['present', 'absent', 'late']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
