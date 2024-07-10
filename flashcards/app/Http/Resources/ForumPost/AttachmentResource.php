<?php

namespace App\Http\Resources\ForumPost;

use App\Models\Course;
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
        $res = [
            'id' => $this->id,
            'title' => $this->title,
            'attachable_type' => Str::of( $this->attachable_type )->classBasename()->lower(),
        ];

        if ( $this->attachable instanceof Course ) {
            $res['attachable_link'] = $this->attachable->getPublicAccessLink()->link;
        }
        else {
            $res['attachable_link'] = $this->attachable_id;
        }

        return $res; 
    }
}
