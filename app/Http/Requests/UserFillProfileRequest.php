<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFillProfileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'birth_date' => 'date',
            'city_id' => 'int|exists:cities,id',
            'university_id' => 'int|exists:universities,id',
            'speciality_id' => 'int|exists:specialities,id',
            'hobbies_ids' => 'array',
        ];
    }
}
