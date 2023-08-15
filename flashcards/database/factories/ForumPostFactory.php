<?php

namespace Database\Factories;

use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumPost>
 */
class ForumPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $created_at = $this->faker->dateTimeBetween( '-10 days' );

        return [
            'title' => $this->faker->sentence( 
                $this->faker->numberBetween( 3, 8 )
            ),
            'content' => $this->faker->paragraphs( 3, true ),
            'user_id' => User::all()->random()->id,
            'forum_topic_id' => ForumTopic::all()->random()->id,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
