<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\CreateUser;
use App\Http\Requests\User\UpdateUser;
use App\Services\DataTable\DataTable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $datatable = new DataTable([ 'name', 'email' ], [ 'name', 'email' ]);
        $datatable->applyUserFilters( User::query(), $request );

        return view( 'user.index', [
            'users' => $datatable->getPaginated(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view( 'user.create' );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( CreateUser $request )
    {
        $user = new User( $request->validated() );
        $user->save();

        return redirect( route( 'user.index' ) );
    }

    /**
     * Display the specified resource.
     */
    public function show( User $user )
    {
        return view( 'user.show', [
            'user' => $user,
            'decks' => $user->decks()->paginate( 25 )
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( User $user )
    {
        return view( 'user.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( UpdateUser $request, User $user )
    {
        $user->fill( $request->validated() );
        $user->save();

        return redirect( route( 'user.edit', $user ) );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( User $user )
    {
        $user->delete();

        return redirect( route( 'user.index' ) );
    }
}
