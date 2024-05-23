<?php

namespace App\Providers;

use App\Models\PasswordReset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\DeckService;
use App\Services\Mail\MailjetTransport;
use App\Services\QuizGeneration\CardRaters\CardRater;
use App\Services\QuizGeneration\CardRaters\ProgressBasedCardRater;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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
        
        Response::macro( 'error', function ( string $message, int $status_code = 400, string $required_action = null ) {
            $response = [
                'message' => $message,
            ];

            if ( !empty( $required_action ) ) {
                $response['required_action'] = $required_action;
            }

            return Response::json( $response, $status_code );
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
        Mail::extend( 'mailjet', function ( array $config ) {
            return new MailjetTransport(
                $config['api_key'],
                $config['secret_key']
            );
        });

        Route::bind( 'password_reset', function ( string $value ) {
            return PasswordReset::where( 'code', $value )
                ->where( 'expires_at', '>', Carbon::now() )
                ->firstOrFail();
        });
    }
}
