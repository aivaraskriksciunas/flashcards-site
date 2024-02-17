<?php

namespace App\Http\Requests\Deck\Api;

use App\Enums\FlashcardType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeck extends FormRequest
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
            'cards.*.id' => 'nullable|numeric',
            'cards.*.question' => 'required|max:200',
            'cards.*.answer' => 'required|max:200',
            'cards.*.comment' => 'nullable|string|max:800',
            'cards.*.question_type' => [
                'nullable',
                Rule::enum( FlashcardType::class )
            ],
            'cards.*.answer_type' => [
                'nullable',
                Rule::enum( FlashcardType::class )
            ],
        ];
    }
}
