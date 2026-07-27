<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesIndentQuantityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'order_quantity.required' => 'Order quantity is required.',
            'order_quantity.integer' => 'Order quantity must be a whole number.',
            'order_quantity.min' => 'Order quantity must be at least 1.',
        ];
    }
}
