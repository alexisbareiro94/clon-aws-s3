<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\UploadedFile;

class ObjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function validationData()
    {
        $data = $this->all();

        if (isset($data['files']) && $data['files'] instanceof UploadedFile) {
            $data['files'] = [$data['files']];
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:256000'], // 50MB máximo por archivo, ajustable
            'visibility' => ['required', 'string', 'in:pr,pu'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'files.required' => 'Debe seleccionar al menos un archivo.',
            'files.array' => 'Los archivos deben enviarse como un arreglo.',
            'files.*.required' => 'El archivo es requerido.',
            'files.*.file' => 'El archivo debe ser un archivo válido.',
            'files.*.max' => 'El archivo no puede superar los 50MB.',
            'files.*.uploaded' => 'Uno de los archivos excedió el tamaño máximo permitido por el servidor (php.ini) o hubo un error de transferencia.',
            'visibility.required' => 'La visibilidad es requerida.',
            'visibility.in' => 'La visibilidad debe ser private o public.',
            'storage_class.required' => 'La clase de almacenamiento es requerida.',
            'storage_class.in' => 'La clase de almacenamiento debe ser standard, infrequent o glacier.',
            'metadata.array' => 'Los metadatos deben ser un array.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422));
        }

        throw new HttpResponseException(redirect()->back()->with('error', $validator->errors()->first()));
    }

    /**
     * protected $fillable = [
        'bucket_id',
        'user_id',
        'object_key',
        'original_name',
        'storage_disk',
        'storage_path',
        'mime_type',
        'size_bytes',
        'checksum',
        'visibility',
        'metadata',
    ];
     */
}
