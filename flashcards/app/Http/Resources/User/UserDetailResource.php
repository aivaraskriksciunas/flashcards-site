<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Account\AccountResource;
use App\Http\Resources\Organization\OrganizationResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email ?? $this->parentAccount->email,
            'name' => $this->name ?? $this->parentAccount->name,
            'is_valid' => $this->is_valid,
            'account_type' => $this->account_type,
            'last_login' => $this->last_login,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'organization' => $this->when( $this->organization_id, new OrganizationResource( $this->organization ) ),
            'accounts' => AccountResource::collection( $this->getAllAccounts() ),
        ];
    }
}
