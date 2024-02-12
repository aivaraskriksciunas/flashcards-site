<?php

namespace App\Http\Requests\Courses\Api;

use App\Enums\CoursePageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCoursePage extends FormRequest
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
            'title' => 'required|max:150',
            'type' => [
                Rule::enum( CoursePageType::class ),
            ],
            'order' => 'numeric|min:1'
        ];
    }
}
