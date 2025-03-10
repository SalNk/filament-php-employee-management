<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->firstName(),
            'address' => fake()->address,
            'zip_code' => fake()->postcode(),
            'birth_day' => fake()->date(),
            'date_hired' => fake()->date(),
            'country_id' => Country::all()->random()->id,
            'city_id' => City::all()->random()->id,
            'department_id' => Department::all()->random()->id,
        ];
    }
}
