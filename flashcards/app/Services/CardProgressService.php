<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Flashcard;
use App\Models\FlashcardProgress;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Exceptions\Quiz\QuizItemAlreadyAnswered;
use App\Services\LearnLevels;

class CardProgressService 
{
    /**
     * Gets this card's progress for this user
     *
     * @param Flashcard $card
     * @param User $user
     * @return FlashcardProgress
     */
    public function get_card_progress( Flashcard $card, User $user )
    {
        return FlashcardProgress::firstOrCreate(
            [ 'flashcard_id' => $card->id, 'user_id' => $user->id ]
        );
    }

    /**
     * Updates the record for this card in the database
     *
     * @param Flashcard $card
     * @param User $user
     * @param boolean $is_correct
     * @return FlashcardProgress
     */
    public function update_card_progress( Flashcard $card, User $user, bool $is_correct ) 
    {
        $progress = $this->get_card_progress( $card, $user );

        if ( $is_correct ) {
            $progress->ncorrect += 1;
        }
        else {
            $progress->nwrong += 1;
        }

        $progress->is_last_wrong = !$is_correct;
        $progress->last_review = \Carbon\Carbon::now();
        // Increase learn level by one, or reset it to 0 if mistake made
        $progress->learn_level = $is_correct ? min( $progress->learn_level + 1, LearnLevels::MAX_LEARN_LEVEL ) : 0;
        // Store recommended date for next learn date
        $progress->next_revision = \Carbon\Carbon::now()->addDays( LearnLevels::LEARN_LEVELS[$progress->learn_level] );
        $progress->save();

        return $progress;
    }

    /**
     * Update this quiz progress and finishes quiz if needed
     *
     * @param QuizItem $quizItem
     * @param boolean $is_correct
     * @return QuizItem
     */
    public function update_quiz_item_progress( QuizItem $quizItem, bool $is_correct )
    {
        // Update quiz item record
        if ( $quizItem->date_answered != null ) {
            throw new QuizItemAlreadyAnswered();
        }

        $quizItem->is_correct = $is_correct;
        $quizItem->date_answered = \Carbon\Carbon::now();
        $quizItem->save();

        if ( $this->should_close_quiz( $quizItem->quiz ) ) {
            $quiz = $quizItem->quiz;
            $quiz->date_taken = \Carbon\Carbon::now();
            $quiz->save();
        }

        return $quizItem;
    }

    /**
     * Check if all quiz items have been answered
     *
     * @param Quiz $quiz
     * @return boolean
     */
    private function should_close_quiz( Quiz $quiz )
    {
        return $quiz->items()->whereNull( 'date_answered' )->count() == 0;
    }
}