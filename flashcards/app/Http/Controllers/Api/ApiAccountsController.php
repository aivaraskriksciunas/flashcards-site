<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Api\UpdateAccount;
use App\Http\Resources\User\UserResource;
use App\Services\Accounts\AccountManager;
use App\Services\Mail\ConfirmationEmailSender;
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

    public function update( UpdateAccount $request )
    {
        $user = $request->user();
        $user->update( $request->validated() );

        if ( $user->wasChanged( 'email' ) ) 
        {
            $user->is_valid = false;
            $user->save();
            ConfirmationEmailSender::send( $user );
        }

        return new UserResource( $user );
    }
}
