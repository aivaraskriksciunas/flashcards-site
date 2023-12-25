<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Account\OrganizationAlreadyAssigned;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\Api\RegisterOrganization;
use App\Http\Resources\Organization\OrganizationResource;
use App\Models\Organization;

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
}
