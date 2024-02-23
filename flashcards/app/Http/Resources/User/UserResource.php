<?php

namespace App\Http\Resources\User;

use App\Enums\UserType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parent = $this->getParentAccount();

        return [
            'name' => $parent->name,
            'is_admin' => $this->when( $this->account_type == UserType::ADMIN, true ),
            'date_joined' => $this->created_at,
        ];
    }
}
