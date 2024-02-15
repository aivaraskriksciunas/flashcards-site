<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Deck;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\Flashcard;
use App\Http\Controllers\Controller;
use App\Services\QuizGeneration\QuizGenerationService;
use App\Services\CardProgressService;
use App\Http\Resources\Quiz\QuizDetailResource;
use App\Http\Requests\Quiz\ProgressReport;
use App\Http\Resources\Quiz\QuizSummaryResource;

class ApiQuizController extends Controller
{
    public function __construct(
        private QuizGenerationService $generator,
        private CardProgressService $progressService,
    ) {}

    /**
     * Returns a generated quiz for this user
     *
     * @param Request $request
     * @param Deck $deck
     * @return QuizDetailResource
     */
    public function get( Request $request, Deck $deck )
    {
        $this->generator->set_preferred_quiz_size( $request->query( 'quiz-size', null ) );
        
        return new QuizDetailResource(
            $this->generator->get_or_generate( $deck, $request->user() )
        );
    }

    public function report_quiz_item_progress( ProgressReport $request, QuizItem $quizItem )
    {
        $is_correct = $request->input( 'is_correct' );

        $savedItem = $this->progressService->update_quiz_item_progress( $quizItem, $is_correct );
        $this->progressService->update_card_progress( 
            $quizItem->card,
            $request->user(),
            $is_correct
        );

        return response()->json([
            'message' => 'Quiz item updated',
            'quiz_item' => $savedItem,
        ]);
    }

    public function report_card_progress( ProgressReport $request, Flashcard $card )
    {
        $is_correct = $request->input( 'is_correct' );

        $this->progressService->update_card_progress( 
            $card,
            $request->user(),
            $is_correct
        );

        return response()->json([
            'message' => 'Flashcard progress saved',
        ]);
    }

    public function get_quiz_summary( Quiz $quiz )
    {
        return new QuizSummaryResource( $quiz );
    }
}
