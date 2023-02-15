<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deck\Api\CreateDeck;
use App\Http\Resources\Deck\DeckResource;
use App\Models\Deck;
use App\Models\Flashcard;
use Illuminate\Http\Request;

class ApiDeckController extends Controller
{
    /**
     * Get a list of decks for this registered user
     *
     * @param Request $request
     */
    public function index( Request $request ) 
    {
        
    }

    public function create( CreateDeck $request )
    {
        $data = $request->validated();

        // Create deck
        Deck $deck = new Deck();
        $deck->name = $data['name'];
        $deck->save();

        // Assign flashcards
        foreach ( $data['cards'] as $card ) 
        {
            $deck->flashcards()->save( new Flashcard( $card ) );
        }

        return new DeckResource( $deck );
    }
}
