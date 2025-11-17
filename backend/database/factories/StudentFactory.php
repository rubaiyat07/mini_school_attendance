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
        $classes = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
        $class = $this->faker->randomElement($classes);
        $sections = ['A', 'B', 'C', 'D']; // Assuming sections are the same for all classes

        return [
            'name' => $this->faker->name(),
            'student_id' => $this->faker->unique()->numerify('S####'),
            'roll' => $this->faker->unique()->numberBetween(1, 50),
            'class' => $class,
            'section' => $this->faker->randomElement($sections),
            'photo' => null,
        ];
    }
}
