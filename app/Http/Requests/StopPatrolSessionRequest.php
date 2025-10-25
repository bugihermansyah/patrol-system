<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StopPatrolSessionRequest extends FormRequest
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
            'client_end_id' => 'required|uuid',
            'end_at' => 'nullable|datetime',
            'end_lat' => 'nullable|numeric|between:-90,90',
            'end_lon' => 'nullable|numeric|between:-180,180',
        ];
    }
}
