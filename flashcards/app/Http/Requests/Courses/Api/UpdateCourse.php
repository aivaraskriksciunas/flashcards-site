<?php

namespace App\Http\Requests\Courses\Api;

use App\Enums\CourseVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourse extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|min:3|max:150',
            'visibility' => [
                'sometimes',
                'required',
                Rule::enum( CourseVisibility::class )
            ],
            'is_unlocked' => 'sometimes|required|boolean',
        ];
    }
}
