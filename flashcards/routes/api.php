<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDeckController;
use App\Http\Controllers\Api\ApiForumCommentController;
use App\Http\Controllers\Api\ApiLibraryController;
use App\Http\Controllers\Api\ApiQuizController;
use App\Http\Controllers\Api\ApiForumPostController;
use App\Http\Controllers\Api\ApiImportController;

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
Route::post( '/google-login', [ ApiAuthController::class, 'googleLogin' ]);
Route::post( '/google-link', [ ApiAuthController::class, 'linkGoogleAccount' ]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware( 'auth:sanctum' )->group( function() {

    Route::get( '/decks', [ ApiDeckController::class, 'index' ] )->name( 'decks.get' );
    Route::post( '/decks', [ ApiDeckController::class, 'create' ] )->name( 'decks.create' );
    Route::patch( '/decks/{deck}', [ ApiDeckController::class, 'update' ] )
        ->middleware( 'can:update,deck')
        ->name( 'decks.update' );
    Route::get( '/decks/{deck}/summary', [ ApiDeckController::class, 'get_deck_summary' ] );

    /**
     * Quiz endpoints
     */
    Route::get( '/decks/{deck}/quiz', [ ApiQuizController::class, 'get' ] )->name( 'quiz.generate' );
    Route::post( '/cards/{card}/progress', [ ApiQuizController::class, 'report_card_progress' ]);
    Route::post( '/quiz/item/{quizItem}/progress', [ ApiQuizController::class, 'report_quiz_item_progress' ]);
    Route::get( '/quiz/{quiz}', [ ApiQuizController::class, 'get_quiz_summary' ] )
        ->middleware( 'can:view,quiz' )
        ->name( 'quiz.view' );

    Route::get( '/library', [ ApiLibraryController::class, 'index' ]);

    /**
     * Forum
     */
    Route::get( 'forum-posts/list/{forumTopic}', [ ApiForumPostController::class, 'getPostList' ] );
    Route::get( 'forum-posts/list/', [ ApiForumPostController::class, 'getPostList' ] );
    Route::post( 'forum-posts/react/{forumPost}', [ ApiForumPostController::class, 'reactToForumPost' ])
        ->name( 'react-to-forum-post' );
    Route::apiResource( 'forum-posts', ApiForumPostController::class )
        ->except([ 'index', 'store' ]);
    Route::post( 'forum-posts', [ ApiForumPostController::class, 'store' ] )
        ->middleware( 'limit-forum-posts' );

    Route::get( '/forum-topics', [ ApiForumPostController::class, 'getTopicList' ]);

    /**
     * Forum comments
     */
    Route::apiResource( 'forum-posts.comments', ApiForumCommentController::class )
        ->shallow()
        ->except([ 'store' ]);

    Route::post( 'forum-posts/{forum_post}/comments', [ ApiForumCommentController::class, 'store' ] )
        ->middleware( 'limit-forum-comments' )
        ->name( 'forum-posts.comments.store' );

    Route::post( 'comments/react/{forumComment}', [ ApiForumCommentController::class, 'reactToForumComment' ] )
        ->name( 'react-to-forum-comment' );

    /**
     * Importing
     */
    Route::post( 'import/quizlet', [ ApiImportController::class, 'import_quizlet_set' ] )->name( 'import-quizlet' );
    Route::post( 'import/anki', [ ApiImportController::class, 'import_anki_set' ])->name( 'import-anki' );
});

/**
 * Routes that can be accessed anonymously
 */
Route::get( '/decks/{deck}', [ ApiDeckController::class, 'get' ] )
    ->middleware( 'can:view,deck' )
    ->name( 'decks.get' );
