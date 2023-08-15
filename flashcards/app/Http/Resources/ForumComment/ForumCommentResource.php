<?php

namespace App\Http\Resources\ForumComment;

use App\Http\Resources\ForumPost\ForumPostReactionResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumCommentResource extends JsonResource
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
            'content' => $this->content,
            'user' => new UserResource( $this->user ),
            'reactions' => new ForumPostReactionResource( $this ),
            'created_at' => $this->created_at,
            'human_created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at,
            'human_updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
