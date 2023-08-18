<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\DeckService;
use App\Services\DeckSummaryService;
use App\Services\NotificationService;
use App\Services\QuizGeneration\CardRaters\CardRater;
use App\Services\QuizGeneration\CardRaters\ProgressBasedCardRater;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( DeckService::class, function ( $app ) {
            return new DeckService();
        });

        $this->app->bind( CardRater::class, ProgressBasedCardRater::class );
        
        Response::macro( 'error', function ( string $message, int $status_code = 400 ) {
            return Response::json([
                'message' => $message,
            ], $status_code );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
        Paginator::useBootstrapFive();
    }
}
