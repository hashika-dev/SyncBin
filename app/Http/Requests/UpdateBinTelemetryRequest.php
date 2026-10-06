<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBinTelemetryRequest extends FormRequest
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
            'bin_id' => ['required'],
            'fullness_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'level' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'distance_mm' => ['nullable', 'numeric', 'min:0'],
            'distance_cm' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'bin_id.required' => 'The bin_id identifier is required.',
            'fullness_percent.min' => 'Fullness percentage cannot be negative.',
            'fullness_percent.max' => 'Fullness percentage cannot exceed 100%.',
        ];
    }
}
