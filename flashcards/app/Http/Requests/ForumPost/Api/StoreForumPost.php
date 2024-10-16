<?php

namespace App\Http\Requests\ForumPost\Api;

use App\Enums\ForumPostAttachmentType;
use App\Exceptions\Forum\ForumPostRateLimitReached;
use App\Models\ForumPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreForumPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:10',
            'content' => 'required',
            'forum_topic' => 'required|exists:forum_topics,slug',
            // 'attachments' => 'array:id,type,title',
            'attachments.*.type' => [
                'required',
                Rule::enum( ForumPostAttachmentType::class )
            ],
            'attachments.*.id' => 'required',
            'attachments.*.title' => 'required|string|min:3|max:150'
        ];
    }
}
