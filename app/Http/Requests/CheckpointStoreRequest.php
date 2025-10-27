<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckpointStoreRequest extends FormRequest
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
            'client_id'     => 'required|uuid',
            'patrol_id'     => 'nullable|integer|exists:patrol_sessions,id', // hanya jika tidak pakai route binding
            'checkpoint_id' => 'nullable|integer|exists:checkpoints,id',

            'qr_scanned'    => 'nullable|string|max:255',
            'is_valid'      => 'nullable|boolean',
            'scanned_at'    => 'nullable|date',

            'lat'           => 'required|numeric|between:-90,90',
            'lon'           => 'required|numeric|between:-180,180',

            'note'          => 'nullable|string|max:1000',

            // MULTI FOTO (opsional)
            'photos'        => 'nullable|array',
            'photos.*'      => 'file|image|max:1300', // 1,3MB per file
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $isValid = filter_var($this->input('is_valid', true), FILTER_VALIDATE_BOOLEAN);
            if ($isValid && !$this->filled('checkpoint_id')) {
                $v->errors()->add('checkpoint_id', 'checkpoint_id wajib jika is_valid = true');
            }
        });
    }
}
