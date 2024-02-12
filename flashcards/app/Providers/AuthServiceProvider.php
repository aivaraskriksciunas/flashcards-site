<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        '\App\Models\Deck' => '\App\Policies\DeckPolicy',
        '\App\Models\ForumPost' => '\App\Policies\ForumPostPolicy',
        '\App\Models\Quiz' => '\App\Policies\QuizPolicy',
        '\App\Models\Course' => '\App\Policies\CoursePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
