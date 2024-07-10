<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shows only the information that is available to public and students.
 */
class CourseDetailPublicResource extends JsonResource
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
            'title' => $this->title,
            'pages' => CoursePageResource::collection( $this->coursePages()->orderBy( 'order', 'ASC' )->get() ),
            'visibility' => $this->visibility,
            'is_unlocked' => $this->is_unlocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
