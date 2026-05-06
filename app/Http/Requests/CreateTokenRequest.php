<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token_name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'expires_option' => 'nullable|string|in:hours,weeks,custom,unlimited',
            'expires_hours' => 'nullable|required_if:expires_option,hours|integer|min:1',
            'expires_weeks' => 'nullable|required_if:expires_option,weeks|integer|min:1',
            'expires_at' => 'nullable|required_if:expires_option,custom|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'token_name.required' => 'El nombre es requerido',
            'permissions.required' => 'Las habilidades son requeridas',
        ];
    }
}
