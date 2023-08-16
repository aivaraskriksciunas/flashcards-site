<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLogin;
use App\Http\Requests\Auth\ApiRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Auth\EmailAlreadyExists;
use App\Exceptions\Auth\IncorrectCredentials;
use Carbon\Carbon;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function login( ApiLogin $request ) 
    {
        $creds = $request->validated();
        
        if ( !Auth::attempt( $creds ) )
        {
            $request->session()->regenerate();

            throw new IncorrectCredentials();
        }

        $user = Auth::user();
        $user->last_login = Carbon::now();
        $user->save();

        return response()->json([
            'token' => $user->createToken( 'browser-token' )->plainTextToken,
        ]);
    }

    public function register( ApiRegister $request )
    {
        $data = $request->validated();

        if ( DB::table( 'users' )->where( 'email', $data['email'] )->count() ) 
        {
            throw new EmailAlreadyExists();
        }

        $user = new User( $data );
        $user->is_admin = false;
        $user->save();

        Auth::login( $user );

        return response()->json([
            'token' => $user->createToken( 'browser-token' )->plainTextToken,
        ]);
    }
}
