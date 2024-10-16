<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\ForumTopicController;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\UserLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([ 'throttle:login' ])->group( function() {
    Route::get( '/login', [ AuthController::class, 'show_login' ] )->name( 'login.show' );
    Route::post( '/login', [ AuthController::class, 'login' ] )->name( 'login' );
});

Route::get( '/logout', [ AuthController::class, 'logout' ] )->name( 'logout' );

Route::middleware([ 'auth', 'is-admin' ])->group( function () {

    Route::get( '/resend-confirmation-email', [ ApiAuthController::class, 'sendConfirmationEmail']);
    Route::get( '/', [ PageController::class, 'home' ] )->name( 'home' );

    Route::resource( 'admin-user', AdminUserController::class )->only( [ 'index', 'store', 'create' ]);
    Route::resource( 'user', UserController::class );
    Route::resource( 'user.deck', DeckController::class )->except( 'index' )->shallow();
    Route::resource( 'forum-topic', ForumTopicController::class );

    Route::get( 'forum/', [ ForumController::class, 'index' ] )->name( 'forum.index' );

    Route::get( '/logs/user/{user}', [ UserLogController::class, 'showUserLogs' ] )->name( 'user-logs.user' );
    Route::get( '/logs/{log}', [ UserLogController::class, 'show' ] )->name( 'user-logs.show' );
});
