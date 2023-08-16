<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Deck\CreateDeck;
use App\Models\Deck;
use Illuminate\Support\Carbon;

class DeckController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create( User $user )
    {
        return view( 'decks.create', [
            'user' => $user
        ] );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( CreateDeck $request, User $user )
    {
        $deck = new Deck( $request->validated() );
        $deck->created_at = Carbon::now();
        $deck->user()->associate( $user );

        $deck->save();
        return redirect( route( 'deck.show', $deck ) );
    }

    /**
     * Display the specified resource.
     */
    public function show( Deck $deck )
    {
        return view( 'decks.show', [
            'deck' => $deck,
            'user' => $deck->user,
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Deck $deck )
    {
        return view( 'decks.edit', [
            'deck' => $deck,
            'user' => $deck->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( CreateDeck $request, Deck $deck )
    {
        $deck->fill( $request->validated() );
        $deck->save();

        return redirect( route( 'deck.show', $deck ) );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Deck $deck )
    {
        $redirect = route( 'user.show', $deck->user );

        $deck->delete();

        return redirect( $redirect );
    }
}
