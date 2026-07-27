<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeductStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:50',
            'updated_by' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
