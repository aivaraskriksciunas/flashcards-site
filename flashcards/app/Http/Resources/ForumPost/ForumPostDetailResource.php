<?php

namespace App\Http\Resources\ForumPost;

use App\Http\Resources\ForumComment\ForumCommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\ForumTopic\ForumTopicResource;

class ForumPostDetailResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'user' => new UserResource( $this->user ),
            'forum_post' => new ForumTopicResource( $this->forumTopic ),
            'reactions' => new ForumPostReactionResource( $this ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'human_created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
