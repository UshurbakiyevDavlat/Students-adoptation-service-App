<?php

namespace App\Http\Requests\Map;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserMapLocationUpdateRequest extends FormRequest
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
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        //write your business logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}