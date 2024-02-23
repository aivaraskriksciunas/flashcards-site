<?php

namespace Database\Factories;

use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'account_type' => $this->faker->randomElement([ 
                UserType::ORG_MANAGER, UserType::ORG_ADMIN, UserType::ORG_MEMBER
            ]),
            'valid_until' => Carbon::now()->addDay(),
        ];
    }
}
