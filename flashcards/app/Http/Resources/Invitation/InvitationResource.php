<?php

namespace App\Http\Resources\Invitation;

use App\Http\Resources\Organization\OrganizationResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'account_type' => $this->account_type,
            'creator' => new UserResource( $this->creator ),
            'organization' => new OrganizationResource( $this->organization ),
            'valid_until' => $this->valid_until,
            'created_at' => $this->created_at,
        ];
    }
}
