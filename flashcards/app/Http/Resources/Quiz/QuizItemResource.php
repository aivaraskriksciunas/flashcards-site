<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PDO;

class QuizItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_answered' => $this->date_answered,
            'card' => [
                'id' => $this->card->id,
                ...$this->card->formatQAPair( $request->query( 'quiz-mode' ) ),
                'comment' => $this->card->comment,
            ]
        ];
    }

}
