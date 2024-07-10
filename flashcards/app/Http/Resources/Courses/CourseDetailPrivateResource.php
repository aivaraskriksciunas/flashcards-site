<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Used for displaying course for owners or managers of the course. Not meant for public visitors/students
 */
class CourseDetailPrivateResource extends JsonResource
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
            'id' => $this->when( $request->user()->can( 'update', $this->resource ), $this->id ),
            'title' => $this->title,
            'pages' => CoursePageResource::collection( $this->coursePages()->orderBy( 'order', 'ASC' )->get() ),
            'visibility' => $this->visibility,
            'is_unlocked' => $this->is_unlocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
