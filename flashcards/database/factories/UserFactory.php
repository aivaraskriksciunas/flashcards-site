<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_valid' => true,
            'account_type' => UserType::STUDENT,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_valid' => false,
            ];
        });
    }

    public function admin() 
    {
        return $this->state( function ( array $attributes ) {
            return [
                'account_type' => UserType::ADMIN,
            ];
        });
    }

    public function orgAdmin() 
    {
        return $this->state( function ( array $attributes ) {
            return [
                'account_type' => UserType::ORG_ADMIN,
            ];
        });
    }

    public function orgMember()
    {
        return $this->state( function ( array $attributes ) {
            return [
                'account_type' => UserType::ORG_MEMBER,
            ];
        });
    }
}
