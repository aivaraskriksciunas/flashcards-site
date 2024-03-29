<?php

namespace App\Http\Requests\Organization\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterOrganization extends FormRequest
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
            'name' => 'required|max:100|min:2',
            'type' => [
                'required',
                Rule::in([
                    'for-profit',
                    'non-profit',
                    'government',
                    'education',
                ]),
            ],
        ];
    }
}
