<?php 

namespace App\Services\Accounts;

use App\Exceptions\Account\StudentAccountAlreadyExists;
use App\Exceptions\Auth\EmailAlreadyExists;
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
        // Make sure this email is unique
        if ( DB::table( 'users' )->where( 'email', $payload->email )->count() ) 
        {
            throw new EmailAlreadyExists();
        }

        $user = new User();
        $user->name = $payload->name;
        $user->email = $payload->email;
        $user->password = $payload->password;
        $user->account_type = User::USER_STUDENT;
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
        // Make sure this email is unique
        if ( DB::table( 'users' )->where( 'email', $payload->email )->count() ) 
        {
            throw new EmailAlreadyExists();
        }

        $user = new User();
        $user->name = $payload->name;
        $user->email = $payload->email;
        $user->password = $payload->password;
        $user->account_type = User::USER_ORG_ADMIN;
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
            User::USER_STUDENT 
        );

        // Make sure a student account does not exist yet
        if ( $accounts->isNotEmpty() ) {
            throw new StudentAccountAlreadyExists();
        }

        $student = new User();
        $student->account_type = User::USER_STUDENT;
        $student->is_valid = true;
        $student->name = $user->name;
        $student->parentAccount()->associate( $user );
        $student->save();

        return $student;
    }

    /**
     * Filter a collection of accounts by the given type
     *
     * @param Collection $accounts a collection of accounts to filter
     * @param string $type account type string
     * @return Collection filtered collection
     */
    private function filterAccountsByType( Collection $accounts, string $type )
    {
        return $accounts->filter( function ( User $item ) use ( $type ) {
            return $item->account_type === $type;
        });
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