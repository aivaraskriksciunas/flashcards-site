<?php

namespace App\Exceptions;

use App\Exceptions\Auth\GoogleAccountAlreadyExists;
use App\Exceptions\Auth\InvalidGoogleToken;
use App\Exceptions\Auth\IncorrectCredentials;
use App\Exceptions\Auth\InvalidCaptcha;
use App\Exceptions\Forum\ForumCommentRateLimitReached;
use App\Exceptions\Forum\ForumPostRateLimitReached;
use App\Exceptions\Import\DeckImportException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\Quiz\QuizItemAlreadyAnswered;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        QuizItemAlreadyAnswered::class,
        ForumPostRateLimitReached::class,
        ForumCommentRateLimitReached::class,
        DeckImportException::class,
        InvalidGoogleToken::class,
        GoogleAccountAlreadyExists::class,
        IncorrectCredentials::class,
        InvalidCaptcha::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
