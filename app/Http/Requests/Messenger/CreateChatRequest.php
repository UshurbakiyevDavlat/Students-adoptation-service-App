<?php

namespace App\Http\Requests\Messenger;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $authUserId = auth()->user()->getAuthIdentifier();
        return [
            'receiver' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('personal_chats')->where(function ($query) use ($authUserId) {
                    $query->where('first_participant', $authUserId);
                })
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return response()->json(['errors' => $validator->errors()], 422);
    }
}
