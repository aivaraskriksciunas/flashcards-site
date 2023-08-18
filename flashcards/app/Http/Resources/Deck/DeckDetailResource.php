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
            'cards' => $this->getCards( 
                $request->query( 'quiz-mode' ), 
                $request->query( 'choose', false ) 
            ),
        ];
    }

    /**
     * Get a list of cards and format them for json
     *
     * @param boolean $choose if we should select 10 random cards
     * @return void
     */
    private function getCards( ?string $mode, bool $choose = false ) 
    {
        $deck_card_collection = $this->cards;
        if ( $choose ) {
            $deck_card_collection = $deck_card_collection->shuffle()->take( 10 );
        }

        $cards = [];
        foreach ( $deck_card_collection as $card ) {
            $cards[] = [
                'id' => $card->id,
                ...$card->formatQAPair( $mode ),
                'comment' => $card->comment,
            ];
        }

        return $cards;
    }
}
