<?php

namespace App\Http\Resources\ForumPost;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AttachmentResource extends JsonResource
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
            'attachable_id' => $this->attachable_id,
            'attachable_type' => Str::of( $this->attachable_type )->classBasename()->lower(),
            'title' => $this->title,
        ];
    }
}
