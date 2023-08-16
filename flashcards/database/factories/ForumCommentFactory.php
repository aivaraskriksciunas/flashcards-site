<?php

namespace Database\Factories;

use App\Models\ForumPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ForumComment>
 */
class ForumCommentFactory extends Factory
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
            'content' => $this->faker->paragraphs( 3, true ),
            'user_id' => User::all()->random()->id,
            'forum_post_id' => ForumPost::all()->random()->id,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
