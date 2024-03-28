<?php

namespace App\Http\Resources\Courses;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'title' => $this->title,
            'user' => new UserResource( $this->user ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'assigned_at' => $this->when( $this->pivot, function () {
                return $this->pivot->created_at;
            }),
        ];
    }
}
