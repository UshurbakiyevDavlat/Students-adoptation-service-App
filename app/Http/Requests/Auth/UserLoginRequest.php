<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserLoginRequest extends FormRequest
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
    //TODO подумать как сделать валидацию на expires at у code
    public function rules(): array
    {
        return [
            'phone' => 'required|exists:users,phone',
            'password' => 'required|string',
            'code' => 'required|exists:user_entries_code,code',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        //write your business logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422));
    }
}
