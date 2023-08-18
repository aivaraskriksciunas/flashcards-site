<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flashcard>
 */
class FlashcardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'question' => $this->faker->words( $this->faker->numberBetween( 1, 3 ), true ),
            'answer' => $this->faker->words( $this->faker->numberBetween( 1, 3 ), true ),
            'comment' => $this->faker->words( $this->faker->randomElement([ 0, 0, 0, 2, 3 ]), true ),
        ];
    }
}
