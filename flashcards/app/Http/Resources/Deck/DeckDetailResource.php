<?php

namespace App\Http\Resources\Deck;

use Illuminate\Http\Resources\Json\JsonResource;

class DeckDetailResource extends JsonResource
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
            'name' => $this->name,
            'cards' => $this->getCards(),
        ];
    }

    private function getCards() 
    {
        $cards = [];
        foreach ( $this->cards as $card ) {
            $cards[] = [
                'id' => $card->id,
                'question' => $card->question,
                'answer' => $card->answer,
            ];
        }

        return $cards;
    }
}
