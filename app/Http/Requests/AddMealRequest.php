<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddMealRequest extends FormRequest
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
       $rules = [
        'mealname' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3000',
        'hastypes' => 'required|in:0,1',
        'maincategory_id'=>'required'
    ];

    if ($this->input('hastypes') == 0) {
        $rules = array_merge($rules, [
            'price' => 'required|numeric|min:0',
            'extraprice' => 'nullable|numeric|min:0',
        ]);
    }

    if ($this->input('hastypes') == 1) {
        $rules = array_merge($rules, [
            'tname' => 'required|array|min:1',
            'tname.*' => 'required|string|max:255',
            'tprice' => 'required|array|min:1',
            'tprice.*' => 'required|numeric|min:0',
            'textraprice' => 'nullable|array',
            'textraprice.*' => 'nullable|numeric|min:0',
        ]);
    }

    return $rules;
    }

    protected function failedValidation(Validator $validator): void
{
    throw new HttpResponseException(
        response()->json(['errors' => $validator->errors()], 422)
    );
}
}
