<?php

namespace App\Http\Requests\Messenger;

use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
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
        return [
            'chat_id' => 'required|integer|exists:personal_chats,id',
            'text' => 'required|string',
        ];
    }
}
