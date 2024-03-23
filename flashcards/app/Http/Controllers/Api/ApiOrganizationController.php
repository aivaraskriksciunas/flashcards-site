<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Account\OrganizationAlreadyAssigned;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\Api\RegisterOrganization;
use App\Http\Resources\Invitation\InvitationResource;
use App\Http\Resources\Organization\OrganizationMemberResource;
use App\Http\Resources\Organization\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Services\DataTable\DataTable;

class ApiOrganizationController extends Controller
{
    public function register( RegisterOrganization $request )
    {
        $user = $request->user();
        if ( $user->organization !== null ) {
            throw new OrganizationAlreadyAssigned();
        }

        $org = new Organization( $request->validated() );
        $org->save();

        $user->organization()->associate( $org );
        $user->save();

        return new OrganizationResource( $org );
    }

    public function showMembers( Request $request )
    {
        $this->authorize( 'viewMembers', Organization::class );

        $dt = new DataTable([ 'name' ]);
        $dt->applyUserFilters( $request->user()->organization->users(), $request, );

        return OrganizationMemberResource::collection(
            $dt->getPaginated()
        );
    }

    public function showInvitations( Request $request )
    {
        $this->authorize( 'viewMembers', Organization::class );

        return InvitationResource::collection(
            $request->user()->organization->getValidInvitations()->paginate( 25 )->withQueryString()
        );
    }
}
