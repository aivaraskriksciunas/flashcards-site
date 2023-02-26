<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Deck\DeckResource;
use Illuminate\Http\Request;

class ApiLibraryController extends Controller
{
    /**
     * Gets the contents of the current user library
     *
     * @param Request $request
     */
    public function index( Request $request ) 
    {
        return DeckResource::collection( 
            $request->user()
                ->getLibrary()
                ->decks()
                ->get()
        );
    }
}
