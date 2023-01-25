<?php

namespace App\Http\Requests\Friend;

use App\Enums\Friend\FriendRequestStatusEnum;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class FriendRequestUpdateRequest extends FormRequest
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
            'user_id' => ['required', 'int', 'exists:users,id',
                Rule::unique('users_friends_requests')->where(function ($query) {
                    return $query->where([
                        ['user_id', $this->user_id],
                        ['friend_id', $this->friend_id],
                    ])->where('status', $this->status);
                })],
            'friend_id' => ['required', 'int', 'exists:users,id'],
            'status' => ['required', 'int', new EnumValue(FriendRequestStatusEnum::class)]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        //write your business logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
