<?php

namespace Database\Factories;

use App\Enums\CoursePageType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoursePage>
 */
class CoursePageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence( 3 ),
            'type' => CoursePageType::Page,
            'order' => 1
        ];
    }
}
