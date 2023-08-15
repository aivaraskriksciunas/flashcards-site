<?php

namespace App\Http\Resources\ForumTopic;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumTopicResource extends JsonResource
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
            'title' => ucwords( $this->title ),
            'slug' => $this->slug,
        ];
    }
}
