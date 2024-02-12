<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    public function showUserLogs( Request $request, User $user )
    {
        return view( 'user-logs.index', [
            'user' => $user,
            'logs' => $user->userLogs()->orderBy( 'created_at', 'DESC' )->paginate( 25 )
        ] );
    }

    public function show( Request $request, UserLog $log )
    {
        return view( 'user-logs.show', [
            'entry' => $log,
            'user' => $request->user(),
        ]);
    }
}
