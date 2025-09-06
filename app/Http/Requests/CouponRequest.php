<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CouponRequest extends FormRequest
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
            'code' => 'required|string|max:20|unique:coupons,code',
            'min_order' => 'nullable|numeric|min:0',
            'percent_value' => 'required|numeric|min:1|max:100',
            'expires_date' => 'nullable|date|after:today',
        ];
    }

            protected function failedValidation(Validator $validator): void
      {
    throw new HttpResponseException(
        response()->json(['errors' => $validator->errors()], 422)
    );
           }
}
