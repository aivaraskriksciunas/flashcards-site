<?php

namespace Database\Seeders;

use App\Models\ForumPost;
use App\Models\User;
use App\Models\ForumTopic;
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
        User::factory()->count( 50 )->create();

        ForumTopic::factory()->count( 8 )->create();
        
        ForumPost::factory()->count( 500 )->create();
    }
}
