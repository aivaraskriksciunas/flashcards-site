<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Account\AccountAlreadyInOrganization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invitations\Api\AcceptInvitation;
use App\Http\Requests\Invitations\Api\CreateInvitation;
use App\Http\Resources\Invitation\InvitationResource;
use App\Models\Invitation;
use App\Models\User;
use App\Services\Accounts\AccountManager;
use App\Services\Authentication\PlainAuthenticator;
use Illuminate\Http\Request;

class ApiInvitationController extends Controller
{

    public function __construct(
        private AccountManager $accountManager
    )
    {
    }

    public function show( Invitation $invitation )
    {
        return new InvitationResource( $invitation );
    }

    public function create( CreateInvitation $request )
    {
        if ( $request->user()->organization->hasUser( $request->validated( 'email' ) ) )
        {
            throw new AccountAlreadyInOrganization();
        }

        $invitation = new Invitation( $request->validated() );
        $invitation->creator()->associate( $request->user() );
        $invitation->organization()->associate( $request->user()->organization );
        $invitation->save();

        return new InvitationResource( $invitation );
    }

    public function accept( AcceptInvitation $request, Invitation $invitation )
    {
        $account = $this->accountManager->addAccountFromInvitation( 
            $invitation,
            $request->validated( 'password' ) 
        );

        $invitation->delete();

        $authenticator = new PlainAuthenticator( $account );
        $account = $authenticator->authenticate();

        return response()->json([
            'token' => $account->createToken( User::INVITATION_LOGIN_TOKEN )->plainTextToken,
        ]);
    }
}
