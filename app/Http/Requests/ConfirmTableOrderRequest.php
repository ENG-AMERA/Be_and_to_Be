<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ConfirmTableOrderRequest extends FormRequest
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
            'cart_id'      => 'required|exists:carts,id',
            'note'         => 'nullable|string|max:500',
            'table_number' => 'required|string|max:70',
            'coupon_id'    => 'nullable|exists:coupons,id',
        ];
    }
        protected function failedValidation(Validator $validator): void
      {
    throw new HttpResponseException(
        response()->json(['errors' => $validator->errors()], 422)
    );
     }
}
