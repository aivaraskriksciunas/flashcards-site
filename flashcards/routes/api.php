<?php

use App\Http\Controllers\Api\ApiAccountsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\Courses\ApiCourseAccessLinkController;
use App\Http\Controllers\Api\Courses\ApiCourseController;
use App\Http\Controllers\Api\Courses\ApiCoursePageController;
use App\Http\Controllers\Api\Courses\ApiCoursePageItemController;
use App\Http\Controllers\Api\ApiDeckController;
use App\Http\Controllers\Api\ApiForumCommentController;
use App\Http\Controllers\Api\ApiLibraryController;
use App\Http\Controllers\Api\ApiQuizController;
use App\Http\Controllers\Api\ApiForumPostController;
use App\Http\Controllers\Api\ApiImportController;
use App\Http\Controllers\Api\ApiInvitationController;
use App\Http\Controllers\Api\ApiOrganizationController;
use App\Http\Controllers\Api\Courses\ApiCourseViewController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Middleware\CourseAccessLinks\EnsureCourseAccessLinkIsValid;
use App\Http\Middleware\CourseAccessLinks\EnsureValidCourseSession;
use App\Http\Middleware\Invitations\EnsureInvitationIsValid;

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

/**
 * Login routes
 */
Route::post( '/login', [ ApiAuthController::class, 'login' ])->name( 'login' );
Route::post( '/register', [ ApiAuthController::class, 'register' ])
    ->middleware( 'captcha:REGISTER' )
    ->name( 'register' );
Route::post( '/register-org', [ ApiAuthController::class, 'registerOrganizationAdmin' ])
    ->middleware( 'captcha:REGISTER_ORG' )
    ->name( 'register.org-admin');
Route::post( '/google-login', [ ApiAuthController::class, 'googleLogin' ]);
Route::post( '/google-link', [ ApiAuthController::class, 'linkGoogleAccount' ]);
Route::get( '/verify/{verification_code}', [ ApiAuthController::class, 'verifyAccount' ])->name( 'verify.email' );
Route::post( '/invitations/{invitation}/accept', [ ApiInvitationController::class, 'accept' ] )->name( 'invitation.accept' );
Route::post( '/forgot-password', [ PasswordResetController::class, 'createResetLink' ])->name( 'forgot-password' );
Route::post( '/reset-password/{password_reset}', [ PasswordResetController::class, 'resetPassword' ])->name( 'reset-password' );


/**
 * Forum
 */
Route::get( '/forum-posts/list/{forumTopic}', [ ApiForumPostController::class, 'getPostList' ] );
Route::get( '/forum-posts/list', [ ApiForumPostController::class, 'getPostList' ] );
Route::get( '/forum-posts/{forum_post}', [ ApiForumPostController::class, 'show' ] )
    ->name( 'forum-posts.show' );
Route::get( '/forum-topics', [ ApiForumPostController::class, 'getTopicList' ]);
Route::get( '/forum-topics/{forumTopic}', [ ApiForumPostController::class, 'getForumTopic' ]);


/**
 *  Courses public access (with access links)
 */
Route::middleware([ EnsureCourseAccessLinkIsValid::class ])->group( function() {
    Route::get( 'courses/view/{access_link}', [ ApiCourseViewController::class, 'showCourse' ])
        ->name( 'courses.access-link.show' );
    Route::get( 'courses/view/{access_link}/course_pages/{course_page}', [ ApiCourseViewController::class, 'showCoursePage' ])
        ->name( 'courses.access-link.course_pages.show' );

    Route::get( 'courses/view/{access_link}/session', [ ApiCourseViewController::class, 'getCourseSession' ])
        ->name( 'courses.access-link.session' );
    Route::post( 'courses/view/{access_link}/progress/{course_session}/{course_page}', [ ApiCourseViewController::class, 'storeUserCourseProgress' ])
        ->name( 'courses.access-link.progress' )
        ->middleware( EnsureValidCourseSession::class );

    Route::post( 'courses/view/{access_link}/anonymous', [ ApiCourseViewController::class, 'createAnonymousAccount' ])
        ->name( 'courses.access-link.anonymous-account.create' );
});
 
/**
 * Routes for authenticated users
 */
Route::middleware([ 'auth:sanctum' ])->group( function() {

    /**
     * Account related endpoints
     */
    Route::get( '/user', [ ApiAuthController::class, 'currentUser' ]);
    Route::get( '/account/switch/{user}', [ ApiAuthController::class, 'switchAccount' ])
        ->name( 'accounts.switch' );
    Route::patch( '/accounts', [ ApiAccountsController::class, 'update' ] )->name( 'accounts.update' );
    Route::patch( '/accounts/set-type', [ ApiAccountsController::class, 'setAccountType' ])->name( 'accounts.set-type' );
    Route::post( '/register/org', [ ApiOrganizationController::class, 'register' ])
        ->name( 'register.organization' );

    Route::get( '/resend-confirmation-email', [ ApiAuthController::class, 'sendConfirmationEmail']);

});

/**
 * Routes for authenticated and verified users
 */
Route::middleware([ 'auth:sanctum', 'is-verified', 'is-valid-org-admin', 'is-valid-account-type' ])->group( function() {

    /**
     * Account management
     */
    Route::post( '/accounts', [ ApiAccountsController::class, 'get' ] )->name( 'accounts.get' );
    Route::post( '/accounts/add/student', [ ApiAccountsController::class, 'addStudentAccount' ] )->name( 'accounts.add.student' );

    /**
     * Deck endpoints
     */
    Route::get( '/decks', [ ApiDeckController::class, 'index' ] )->name( 'decks.show' );
    Route::post( '/decks', [ ApiDeckController::class, 'create' ] )->name( 'decks.create' );
    Route::patch( '/decks/{deck}', [ ApiDeckController::class, 'update' ] )
        ->middleware( 'can:update,deck')
        ->name( 'decks.update' );
    Route::delete( '/decks/{deck}', [ ApiDeckController::class, 'delete' ] )->name( 'decks.delete' );
    Route::post( '/decks/{deck}/add-to-library', [ ApiDeckController::class, 'addToLibrary' ])->name( 'decks.add-to-library' );
    Route::post( '/decks/{deck}/copy', [ ApiDeckController::class, 'copy' ])->name( 'decks.copy' );
    Route::get( '/decks/{deck}/summary', [ ApiDeckController::class, 'get_deck_summary' ] );
    Route::post( '/decks/{deck}/draft', [ ApiDeckController::class, 'store_draft' ] )->name( 'decks.draft.create' );
    Route::get( '/decks/{deck}/draft', [ ApiDeckController::class, 'get_draft' ] )->name( 'decks.draft.show' );
    Route::post( '/notes', [ ApiDeckController::class, 'save_note' ] )->name( 'decks.note.create' );
    Route::get( '/notes', [ ApiDeckController::class, 'get_note' ] )->name( 'decks.note.show' );
    Route::delete( '/notes', [ ApiDeckController::class, 'delete_note' ] )->name( 'decks.note.delete' );

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
    Route::post( 'forum-posts', [ ApiForumPostController::class, 'store' ] )
        ->middleware( 'limit-forum-posts' )
        ->name( 'forum-posts.store' );
    Route::post( 'forum-posts/react/{forumPost}', [ ApiForumPostController::class, 'reactToForumPost' ])
        ->name( 'react-to-forum-post' );
    Route::delete( 'forum-post/{forumPost}', [ ApiForumPostController::class, 'destroy' ])->name( 'forum-posts.delete' );

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
    Route::post( 'import/wordlist', [ ApiImportController::class, 'import_wordlist_set' ] )->name( 'import-wordlist' );
    Route::post( 'import/anki', [ ApiImportController::class, 'import_anki_set' ])->name( 'import-anki' );

    /**
     * Organization endpoints
     */
    Route::get( 'organizations/members', [ ApiOrganizationController::class, 'showMembers' ] )->name( 'organizations.members.show' );
    Route::get( 'organizations/invitations', [ ApiOrganizationController::class, 'showInvitations' ] )->name( 'organizations.invitations.show' );

    /**
     * Course endpoints
     */
    Route::apiResource( 'courses', ApiCourseController::class )->except([ 'index' ]);
    Route::post( 'courses/{course}/course_pages/reorder', [ ApiCourseController::class, 'setCoursePageOrder' ] )->name( 'courses.set-page-order' );
    Route::apiResource( 'courses.course_pages', ApiCoursePageController::class )->scoped();
    Route::apiResource( 'courses.course_pages.course_page_items', ApiCoursePageItemController::class )->scoped();
    Route::post( 
            'courses/{course}/course_pages/{course_page}/course_page_items/reorder', 
            [ ApiCoursePageController::class, 'setCoursePageItemOrder' ] 
        )->name( 'courses.course_pages.set-page-item-order' );
    Route::post( 'courses/{course}/assign', [ ApiCourseController::class, 'assignToOrgMember' ])->name( 'courses.assigned.add' );
    Route::get( 'courses/{course}/students', [ ApiCourseController::class, 'getCourseStudents' ])->name( 'courses.students.list' );
    Route::get( 'courses/{course}/assigned', [ ApiCourseController::class, 'getAssignedUsers' ])->name( 'courses.assigned.list' );
    Route::get( 'courses/{course}/assignable', [ ApiCourseController::class, 'getAssignableUsers' ])->name( 'courses.assignable.list' );
    
    /**
     * My course endpoints
     */
    Route::get( 'my-courses', [ ApiCourseController::class, 'getUserCourses' ] )->name( 'my-courses.list' );
    Route::get( 'my-courses/assigned', [ ApiCourseController::class, 'getAssignedUserCourses' ] )->name( 'my-courses.assigned.list' );

    /**
     * Course access links
     */
    Route::apiResource( 'courses.access-links', ApiCourseAccessLinkController::class )->scoped([
        'access_link' => 'id'
    ]);

    /**
     * Invitation endpoints
     */
    Route::post( 'invitations/create', [ ApiInvitationController::class, 'create' ] )->name( 'invitation.create' );
    Route::get( 'invitations/{invitation}', [ ApiInvitationController::class, 'show' ] )->name( 'invitation.show' )
        ->middleware( EnsureInvitationIsValid::class );
});

/**
 * Routes that can be accessed anonymously
 */
Route::get( '/decks/{deck}', [ ApiDeckController::class, 'get' ] )
    ->middleware( 'can:view,deck' )
    ->name( 'decks.get' );

