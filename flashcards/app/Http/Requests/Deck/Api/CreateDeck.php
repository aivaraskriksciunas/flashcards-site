<?php

namespace App\Http\Requests\Deck\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateDeck extends FormRequest
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
            'name' => 'required|min:3|max:100',
            'cards.*.question' => 'required|max:200',
            'cards.*.response' => 'required|max:200',
        ];
    }
}
