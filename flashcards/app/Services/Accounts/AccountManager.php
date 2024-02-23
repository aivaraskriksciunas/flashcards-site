<?php 

namespace App\Services\Accounts;

use App\Enums\UserType;
use App\Exceptions\Account\StudentAccountAlreadyExists;
use App\Exceptions\Auth\EmailAlreadyExists;
use App\Models\Invitation;
use App\Services\Accounts\Payloads\StudentPayload;
use App\Services\Mail\ConfirmationEmailSender;
use App\Models\User;
use App\Services\Accounts\Payloads\OrganizationAdminPayload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountManager 
{

    /**
     * Creates a new user account, if one does not exist for the given email
     *
     * @param StudentPayload $payload
     * @return User created student account
     * @throws EmailAlreadyExists
     */
    public function registerStudentAccount( StudentPayload $payload, bool $sendEmail = true ) : User
    {
        $this->ensureEmailIsUnique( $payload->email );

        $user = new User();
        $user->name = $payload->name;
        $user->email = $payload->email;
        $user->password = $payload->password;
        $user->account_type = UserType::STUDENT;
        $user->is_valid = false;
        $user->save();

        if ( $sendEmail ) {
            ConfirmationEmailSender::send( $user );
        }

        return $user;
    }

    /**
     * Creates a new organization admin account, if one does not exist for the given email
     *
     * @param StudentPayload $payload
     * @return User created student account
     * @throws EmailAlreadyExists
     */
    public function registerOrganizationAdminAccount( 
        OrganizationAdminPayload $payload, 
        bool $sendEmail = true 
    ) : User
    {
        $this->ensureEmailIsUnique( $payload->email );

        $user = new User();
        $user->name = $payload->name;
        $user->email = $payload->email;
        $user->password = $payload->password;
        $user->account_type = UserType::ORG_ADMIN;
        $user->is_valid = false;
        $user->save();

        if ( $sendEmail ) {
            ConfirmationEmailSender::send( $user );
        }

        return $user;
    }

    /**
     * Adds a student account for an existing account
     *
     * @param User $user
     * @return User The newly created student account
     */
    public function addStudentAccount( User $user )
    {
        $user = $user->getParentAccount();

        $accounts = $this->filterAccountsByType(
            $user->getAllAccounts( $user ),
            UserType::STUDENT, 
        );

        // Make sure a student account does not exist yet
        if ( $accounts->isNotEmpty() ) {
            throw new StudentAccountAlreadyExists();
        }

        $student = new User();
        $student->account_type = UserType::STUDENT;
        $student->is_valid = true;
        $student->name = $user->name;
        $student->parentAccount()->associate( $user );
        $student->save();

        return $student;
    }

    /**
     * Accepts an invitation and creates an account, or subaccount if this account already exists
     *
     * @param Invitation $invitation
     * @param string $newPassword Password for the new account
     * @return User newly created account
     */
    public function addAccountFromInvitation( Invitation $invitation, string $newPassword )
    {
        $parent = $this->getParentAccountForEmail( $invitation->email );

        $user = new User();
        $user->name = $invitation->name;
        
        $allowed_roles = [ UserType::ORG_MANAGER, UserType::ORG_ADMIN, UserType::ORG_MEMBER ];
        if ( in_array( $invitation->account_type, $allowed_roles ) ) {
            $user->account_type = $invitation->account_type;
        }
        else {
            $user->account_type = UserType::ORG_MEMBER;
        }

        $user->is_valid = true;
        $user->password = $newPassword;
        $user->organization()->associate(
            $invitation->creator->organization
        );

        if ( $parent ) {
            $user->parentAccount()->associate( $parent );
            $parent->password = $newPassword;
            $parent->save();
        }
        else { 
            $user->email = $invitation->email;
        }

        $user->save();

        return $user;
    }

    /**
     * Filter a collection of accounts by the given type
     *
     * @param Collection $accounts a collection of accounts to filter
     * @param string $type account type string
     * @return Collection filtered collection
     */
    private function filterAccountsByType( Collection $accounts, UserType $type )
    {
        return $accounts->filter( function ( User $item ) use ( $type ) {
            return $item->account_type === $type;
        });
    }

    /**
     * Ensures that the provided email does not already exist in the database
     *
     * @param string $email Email to check
     * @throws \App\Exceptions\Auth\EmailAlreadyExists
     * @return void
     */
    private function ensureEmailIsUnique( string $email )
    {
        // Make sure this email is unique
        if ( DB::table( 'users' )->where( 'email', $email )->count() ) 
        {
            throw new EmailAlreadyExists();
        }
    }

    /**
     * Get a list of user accounts and subaccounts for the given email
     *
     * @param string $email
     * @return \Illuminate\Support\Collection
     */
    private function getAccountsForEmail( string $email ) : User|null 
    {
        $parent = User::where( 'email', $email )
            ->where( 'parent_id', null )
            ->first();

        if ( !$parent ) return collect(); // Return empty collection

        // Return a collection of parent and subaccounts
        return $parent->subAccounts->prepend( $parent );
    }

    /**
     * Gets top level account for the given email
     *
     * @param string $email
     * @return User|null User or null if such account does not exist
     */
    private function getParentAccountForEmail( string $email ) : User|null
    {
        return User::where( 'email', $email )
            ->where( 'parent_id', null )
            ->first();
    }
}