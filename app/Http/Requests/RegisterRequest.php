<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'fullname' => 'required|string|max:255',
          'phonenumber' => [
    'required',
    'regex:/^09(3|4|5|6|8|9)[0-9]{7}$/',
    'unique:users,phonenumber',
],
            'password' => 'required|string|min:8',

        ];
    }

        protected function failedValidation(Validator $validator): void
{
    throw new HttpResponseException(
        response()->json(['errors' => $validator->errors()], 422)
    );
}
}
