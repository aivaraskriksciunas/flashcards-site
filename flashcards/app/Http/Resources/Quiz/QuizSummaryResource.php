<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\QuizItem;

class QuizSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $correct_count = 0;

        return [
            'id' => $this->id,
            'name' => $this->deck->name,
            'date_generated' => $this->date_generated,
            'date_completed' => $this->date_taken,
            'items' => $this->items->map( function ( QuizItem $item, $key ) use ( &$correct_count ) {
                if ( $item->is_correct ) $correct_count++;

                return [
                    'is_correct' => $item->is_correct,
                    'question' => $item->card->question,
                    'answer' => $item->card->answer,
                    'comment' => $item->card->comment,
                    'card_id' => $item->card->id,
                ];
            }),
            'correct_count' => $correct_count,
            'total_cards' => $this->items->count(),
        ];
    }
}
