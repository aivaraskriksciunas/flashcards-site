<?php

namespace Database\Factories;

use App\Enums\CoursePageItemType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoursePageItem>
 */
class CoursePageItemFactory extends Factory
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
            'type' => CoursePageItemType::Text,
            'content' => $this->faker->text(),
            'order' => 1
        ];
    }
}
