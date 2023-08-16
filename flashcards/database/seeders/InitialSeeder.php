<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $initialUser = User::create([
            'name' => 'Administrator', 
            'password' => 'admin',
            'email' => 'aiviskri@gmail.com'
        ]);

        $initialUser->is_admin = true;

        $initialUser->save();
    }
}
