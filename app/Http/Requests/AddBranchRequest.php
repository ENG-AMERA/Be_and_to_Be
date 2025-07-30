<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddBranchRequest extends FormRequest
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
        'branch_name'   => 'required|string|max:255',
        'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
        'length'        => 'required|numeric',
        'width'         => 'required|numeric',
        'instatoken'    => 'nullable|string',
        'facetoken'     => 'nullable|string',
        'phones'        => 'required|array|min:1',
        'phones.*'      => [
    'required',
    'regex:/^09(3|4|5|6|8|9)[0-9]{7}$/',
    'unique:users,phonenumber',
],
        ];
    }

    protected function failedValidation(Validator $validator): void
{
    throw new HttpResponseException(
        response()->json(['errors' => $validator->errors()], 422)
    );
}
}
