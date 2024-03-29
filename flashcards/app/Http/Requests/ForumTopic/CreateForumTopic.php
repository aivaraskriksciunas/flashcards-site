<?php

namespace App\Http\Requests\ForumTopic;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class CreateForumTopic extends FormRequest
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
            'title' => 'required|max:100',
            'slug'  => [
                'required',
                'regex:/[A-Za-z0-9\-]+/',
                'unique:forum_topics,slug',
            ],
        ];
    }
}
