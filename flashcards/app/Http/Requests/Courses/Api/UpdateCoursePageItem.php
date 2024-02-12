<?php

namespace App\Http\Requests\Courses\Api;

use App\Enums\CoursePageItemType;
use App\Rules\RichText;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursePageItem extends FormRequest
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
            'title' => 'nullable|max:150',
            'content' => [ 
                'nullable'
            ],
        ];
    }
}
