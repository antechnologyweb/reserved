<?php

namespace App\Http\Requests\User;

use App\Domain\Contracts\UserContract;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            UserContract::NAME  =>  'nullable|min:2|max:255',
            UserContract::EMAIL =>  'nullable|unique:'.UserContract::TABLE.','.UserContract::EMAIL.','.$this->id,
            UserContract::LANGUAGE_ID   =>  'nullable|exists:languages,id',
            UserContract::EMAIL_NOTIFICATION    =>  'nullable',
            UserContract::PUSH_NOTIFICATION     =>  'nullable',
        ];
    }

    public function validated(): array
    {
        $request    =   $this->validator->validated();
        return $request;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => 'failure',
            'status_code' => 400,
            'message' => 'Bad Request',
            'errors' => $validator->errors(),
        ];
        throw new HttpResponseException(response()->json($response, 400));
    }
}
