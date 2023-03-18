<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDeckController;
use App\Http\Controllers\Api\ApiLibraryController;
use App\Http\Controllers\Api\ApiQuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post( '/login', [ ApiAuthController::class, 'login' ]);
Route::post( '/register', [ ApiAuthController::class, 'register' ]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware( 'auth:sanctum' )->group( function() {

    Route::get( '/decks', [ ApiDeckController::class, 'index' ] );
    Route::get( '/decks/{deck}', [ ApiDeckController::class, 'get' ] )
        ->middleware( 'can:view,deck' );
    Route::post( '/decks', [ ApiDeckController::class, 'create' ] );
    Route::patch( '/decks/{deck}', [ ApiDeckController::class, 'update' ] )
        ->middleware( 'can:update,deck');

    /**
     * Quiz endpoints
     */
    Route::get( '/decks/{deck}/quiz', [ ApiQuizController::class, 'get' ] );
    Route::post( '/cards/{card}/progress', [ ApiQuizController::class, 'report_card_progress' ]);
    Route::post( '/quiz/item/{quizItem}/progress', [ ApiQuizController::class, 'report_quiz_item_progress' ]);


    Route::get( '/library', [ ApiLibraryController::class, 'index' ]);

});
