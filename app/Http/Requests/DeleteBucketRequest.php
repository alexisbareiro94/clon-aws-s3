<?php

namespace App\Http\Requests;

use App\Models\Bucket;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DeleteBucketRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bucket = $this->route('bucket');
        return Gate::allows('delete', $bucket);
    }

    public function rules(): array
    {
        $bucket = $this->route('bucket');

        return [
            'name' => ['required', 'string', 'in:'.$bucket->name],
            'confirm' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.in' => 'El nombre del bucket no coincide.',
            'confirm.required' => 'Debes confirmar la eliminación.',
            'confirm.accepted' => 'Debes confirmar la eliminación.',
        ];
    }
}
