<?php 

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

trait WithPermissions 
{

    /**
     * Returns available permissions that current user has over this model
     *
     * @param JsonResource $model 
     * @return array list of allowed actions
     */
    protected function getPermissions( JsonResource $resource )
    {
        $actions = [ 'view', 'delete', 'update' ];
        $allowed_actions = [];

        foreach ( $actions as $action ) {
            if ( Gate::allows( $action, $resource->resource ) ) {
                $allowed_actions[$action] = true;
            }
        }

        return $allowed_actions;
    }

}