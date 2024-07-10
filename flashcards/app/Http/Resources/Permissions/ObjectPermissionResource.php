<?php

namespace App\Http\Resources\Permissions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ObjectPermissionResource extends JsonResource
{

    private array $permissions;

    /**
     * Constructs resource object
     *
     * @param mixed $resource
     * @param array $permissions list of permissions to check
     */
    public function __construct( $resource, array $permissions = [ 'update', 'delete' ] )
    {   
        parent::__construct( $resource );
        $this->permissions = $permissions;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $available_permissions = [];
        foreach ( $this->permissions as $permission ) 
        {
            if ( $request->user()->can( $permission, $this->resource ) )
            {
                $available_permissions[] = $permission . '_' . Str::snake( class_basename( $this->resource ) );
            }
        }

        return $available_permissions;
    }
}
