<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Api\SetAccountType;
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
        // Update this account
        $user->update( $request->safe()->except([ 'email', 'password' ]) );

        // Update parent account's login information
        $user = $user->getParentAccount();
        $user->email = $request->input( 'email' );
        if ( $request->filled( 'password' ) ) {
            $user->password = $request->input( 'password' );
        }
        $user->save();

        // Force email verification
        if ( $user->wasChanged( 'email' ) ) 
        {
            $user->is_valid = false;
            $user->save();
            ConfirmationEmailSender::send( $user );
        }

        return new UserResource( $user );
    }

    public function setAccountType( SetAccountType $request )
    {
        $user = $request->user();
        if ( $user->account_type === UserType::UNDEFINED )
        {
            $user->account_type = $request->input( 'type' );
            $user->save();
        }

        return new UserResource( $user );
    }
}
