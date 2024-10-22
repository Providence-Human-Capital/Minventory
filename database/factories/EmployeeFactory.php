<?php

namespace Database\Factories;

use App\Models\employees;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = employees::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'uin' => $this->faker->unique()->randomNumber(8), // Use a unique identifier
            'dob' => $this->faker->date,
            'national_id' => $this->faker->unique()->randomNumber(8),
            'country_code' => $this->faker->unique()->randomNumber(4),
            'phone_number' => $this->faker->phoneNumber,
            'employeer' => $this->faker->company,
            'dependant1' => $this->faker->name,
            'dependant1_id' => $this->faker->unique()->randomNumber(8),
            'dependant2' => $this->faker->name,
            'dependant2_id' => $this->faker->unique()->randomNumber(8),
        ];
    }
}
