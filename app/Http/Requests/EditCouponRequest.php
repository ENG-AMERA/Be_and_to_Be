<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EditCouponRequest extends FormRequest
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
            'coupon_id' => 'required|exists:coupons,id',
            'min_order' => 'sometimes|numeric|min:0',
            'value' => 'sometimes|numeric|min:1|max:100',
            'expires_at' => 'sometimes|date|after:today',
        ];

    }

        protected function failedValidation(Validator $validator): void
      {
    throw new HttpResponseException(
        response()->json(['errors' => $validator->errors()], 422)
    );
           }
}
