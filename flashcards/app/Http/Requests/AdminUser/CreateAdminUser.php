<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminUser extends FormRequest
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
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|min:5'
        ];
    }
}
