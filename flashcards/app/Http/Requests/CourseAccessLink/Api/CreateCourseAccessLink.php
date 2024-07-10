<?php

namespace App\Http\Requests\CourseAccessLink\Api;

use App\Enums\CourseAccessLinkType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCourseAccessLink extends FormRequest
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
            'type' => [
                'required',
                Rule::enum( CourseAccessLinkType::class )
            ],
            'name' => 'required|max:100|min:1',
            'expires_at' => 'sometimes|nullable|date|after:today',
        ];
    }
}
