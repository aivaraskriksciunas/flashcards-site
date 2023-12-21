<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Services\Accounts\AccountManager;
use Illuminate\Http\Request;

class ApiAccountsController extends Controller
{
    public function __construct(
        private AccountManager $accountManager
    )
    {}

    public function get( Request $request )
    {

    }

    public function addStudentAccount( Request $request )
    {
        $student = $this->accountManager->addStudentAccount( $request->user() );
        return new UserResource( $student );
    }
}
