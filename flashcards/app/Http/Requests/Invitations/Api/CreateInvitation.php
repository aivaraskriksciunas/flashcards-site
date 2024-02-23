<?php

namespace App\Http\Requests\Invitations\Api;

use App\Enums\UserType;
use App\Models\Invitation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateInvitation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->can( 'create', Invitation::class );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100',
            'name' => 'required|max:200',
            'account_type' => [
                'required',
                Rule::in([ UserType::ORG_ADMIN, UserType::ORG_MANAGER, UserType::ORG_MEMBER ])
            ]
        ];
    }
}
