<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBucketRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'visibility' => ['nullable', 'string', 'in:pr,pu'],
            'name' => ['nullable', 'string', 'min:3', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'visibility.in' => 'La visibilidad debe ser privada o pública.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre debe tener menos de 120 caracteres.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(redirect()->back()->with('error', $validator->errors()->first()));
    }
}
