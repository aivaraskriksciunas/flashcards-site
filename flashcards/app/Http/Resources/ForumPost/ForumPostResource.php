<?php

namespace App\Http\Resources\ForumPost;

use App\Http\Resources\ForumTopic\ForumTopicResource;
use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ForumPostResource extends JsonResource
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
            'content' => Str::limit( $this->content, 100 ),
            'user' => new UserResource( $this->user ),
            'forum_post' => new ForumTopicResource( $this->forumTopic ),
            'reactions' => new ForumPostReactionResource( $this ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'human_created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
