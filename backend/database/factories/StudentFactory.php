<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'student_id' => $this->faker->unique()->numerify('S####'),
            'class' => $this->faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']),
            'section' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'photo' => null,
        ];
    }
}
