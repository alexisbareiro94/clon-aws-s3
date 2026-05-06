<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSharedLinkRequest extends FormRequest
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
    public function prepareForValidation()
    {
        $this->merge([
            'created_by_user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'object_id' => 'required|exists:objects,id',
            'permission' => 'required|in:r,d,rd',
            'expires_at' => 'nullable|date',
            'download_limit' => 'nullable|integer|min:0',
            'created_by_user_id' => 'required|exists:users,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(redirect()->back()->with('error', $validator->errors()->first()));
    }
}
