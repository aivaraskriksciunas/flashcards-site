<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminUser extends FormRequest
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
            'name' => 'required|min:3',
            'email' => [
                'required',
                'email',
                Rule::unique( 'admin_users', 'email' )->ignore( $this->route( 'admin_user' ) )
            ],
            'password' => 'nullable|min:5'
        ];
    }
}
