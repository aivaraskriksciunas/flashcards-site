<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Resources\Json\JsonResource;

class CoursePageDetailResource extends JsonResource
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
            'course_id' => $this->course_id,
            'title' => $this->title,
            'type' => $this->type,
            'order' => $this->order,
            'items' => $this->coursePageItems()->orderBy( 'order', 'ASC' )->get(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
