<?php

namespace App\Http\Resources\CourseAccessLink;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseAccessLinkDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expires_at_human = null;
        if ( $this->expires_at ) {
            $expires_at_human = $this->expires_at->diffForHumans();
        }

        return [
            'id' => $this->id,
            'link' => $this->link,
            'name' => $this->name,
            'course' => $this->course_id,
            'user' => new UserResource( $this->user ),
            'type' => $this->type,
            'expires_at' => $this->expires_at,
            'expires_at_human' => $expires_at_human,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
