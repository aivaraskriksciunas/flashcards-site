<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deck\Api\CreateDeck;
use App\Http\Requests\Deck\Api\UpdateDeck;
use App\Http\Resources\Deck\DeckResource;
use App\Http\Resources\Deck\DeckDetailResource;
use App\Services\DeckService;
use App\Models\Deck;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiDeckController extends Controller
{

    public function __construct( private DeckService $deckService ) {}

    /**
     * Get a list of decks for this registered user
     *
     * @param Request $request
     */
    public function index( Request $request ) 
    {
        return DeckResource::collection( $request->user()->decks()->get() );
    }

    /**
     * Get deck by id
     *
     * @param Request $request
     * @param Deck $deck
     * @return App\Http\Resources\Deck\DeckDetailResource
     */
    public function get( Request $request, Deck $deck )
    {
        return new DeckDetailResource( $deck );
    }

    /**
     * Creates a deck with flashcards
     *
     * @param CreateDeck $request
     */
    public function create( CreateDeck $request )
    {
        $data = $request->validated();

        // Create deck
        $deck = new Deck();
        $deck->name = $data['name'];
        $request->user()->decks()->save( $deck );

        // Now add this deck to user's library
        $request->user()
            ->getLibrary()
            ->decks()
            ->attach( $deck, [ 'created_at' => Carbon::now() ] );

        // Assign flashcards
        if ( isset( $data['cards'] ) ) 
        {
            foreach ( $data['cards'] as $card ) 
            {
                $deck->cards()->save( new Flashcard( $card ) );
            }
        }
        
        return new DeckResource( $deck );
    }

    /**
     * Updates deck along with its flashcards
     *
     * @param UpdateDeck $request
     * @param Deck $deck
     */
    public function update( UpdateDeck $request, Deck $deck )
    {
        $data = $request->validated();

        $deck->name = $data['name'];
        $deck->save();
        
        $this->deckService->updateCards( $deck, $data['cards'] );
        
        return new DeckResource( $deck );
    }
}
