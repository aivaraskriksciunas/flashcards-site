<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizDetailResource extends JsonResource
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
            'date_generated' => $this->date_generated,
            'deck' => [
                'id' => $this->deck_id,
                'name' => $this->deck->name,
            ],
            'items' => QuizItemResource::collection( $this->items )
        ];
    }
}
