<?php

namespace Database\Seeders;

use App\Models\ForumPost;
use App\Models\User;
use App\Models\ForumTopic;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count( 20 )->create();

        ForumTopic::factory()->count( 8 )->create();
        
        ForumPost::factory()->count( 500 )->create();

        $this->createOrganizations();
    }

    private function createOrganizations()
    {
        $orgs = Organization::factory()->count( 10 )->create();

        // Create org administrators
        foreach ( $orgs as $org )
        {
            User::factory()->for( $org )->orgAdmin()->create();
        }

        // Create members for organizations
        $faker = \Faker\Factory::create();
        foreach ( $orgs as $org )
        {
            User::factory()->for( $org )->orgManager()->count( $faker->numberBetween( 0, 5 ) )->create();
            User::factory()->for( $org )->orgMember()->count( $faker->numberBetween( 1, 50 ) )->create();
        }
    }
}
