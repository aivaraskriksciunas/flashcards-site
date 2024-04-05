<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deck\Api\CreateDeck;
use App\Http\Requests\Deck\Api\SaveNote;
use App\Http\Requests\Deck\Api\UpdateDeck;
use App\Http\Resources\Deck\DeckResource;
use App\Http\Resources\Deck\DeckDetailResource;
use App\Services\DeckService;
use App\Models\Deck;
use App\Models\Flashcard;
use App\Services\DeckSummaryService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class ApiDeckController extends Controller
{

    public function __construct( 
        private DeckService $deckService, 
        private DeckSummaryService $deckSummaryService 
    ) {}

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

    /**
     * Stores the draft version of the deck in cache
     *
     * @param UpdateDeck $request
     * @param Deck $deck
     * @return void
     */
    public function store_draft( UpdateDeck $request, Deck $deck )
    {
        $deck->setDraft( $request->validated() );

        return response( true );
    }

    /**
     * Retrieves the stored draft version of the deck
     *
     * @param Request $request
     * @param Deck $deck
     * @return void
     */
    public function get_draft( Deck $deck )
    {
        return response( $deck->getDraft() );
    }

    /**
     * Stores temporary cards in the cache
     *
     * @param SaveNote $request
     * @return void
     */
    public function save_note( SaveNote $request )
    {
        $key_name = "notes:{$request->user()->id}";
        Redis::rpush( $key_name, json_encode( $request->validated() ) );
        Redis::ltrim( $key_name, -100, -1 ); // Limit list length
        Redis::expire( $key_name, 7 * 24 * 60 * 60 ); // Expire in 7 days

        return response( true );
    }

    /**
     * Retrieves the stored note
     *
     * @param Request $request
     * @return void
     */
    public function get_note( Request $request )
    {
        $key_name = "notes:{$request->user()->id}";
        return response( Redis::lrange( $key_name, 0, -1 ) );
    }

    /**
     * Deletes the stored note
     *
     * @param Request $request
     * @return void
     */
    public function delete_note( Request $request )
    {
        $key_name = "notes:{$request->user()->id}";
        Redis::del( $key_name );
        return response( true );
    }

    /**
     * Deletes deck
     *
     * @param Deck $deck
     */
    public function delete( Request $request, Deck $deck )
    {
        // Remove deck from library
        $request->user()
            ->getLibrary()
            ->decks()
            ->detach([ $deck->id ]);

        // If user is the owner, delete deck
        if ( $deck->user == $request->user )
        {
            $deck->delete();
        }

        return response( '', 204 );
    }

    /**
     * Retrieves summary for this deck, such as most recent cards
     *
     * @param Deck $deck
     * @return void
     */
    public function get_deck_summary( Request $request, Deck $deck )
    {
        return $this->deckSummaryService->getDeckSummary( $deck, $request->user() );
    }
}
