<?php

namespace App\Http\Requests;

use App\Models\Bucket;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreBucketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Bucket::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'visibility' => ['required', 'string', 'in:pr,pu'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder 120 caracteres.',
            'visibility.required' => 'La visibilidad es obligatoria.',
            'visibility.in' => 'La visibilidad debe ser privada (pr) o pública (pu).',
        ];
    }
}