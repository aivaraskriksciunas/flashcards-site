<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUser;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $users = User::where( 'is_admin', true )->get();

        return view( 'admin-users.index', [
            'users' => $users
        ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view( 'admin-users.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\User\CreateUser  $request
     */
    public function store( CreateUser $request )
    {
        $user = new User( $request->validated() );
        $user->is_admin = true;
        $user->save();

        return redirect( route( 'admin-user.index' ) );
    }

}
