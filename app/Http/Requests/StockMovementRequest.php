<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementRequest extends FormRequest
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
            'product_code' => [
                'required',
                'integer',
                'exists:products,product_code',
            ],
            'movement_type' => [
                'required',
                'in:PURCHASE, ADJUSTMENT, PROCUREMENT',
            ],
            'quantity_change' => [
                'required',
                'integer',
            ],
            'before_quantity' => [
                'required',
                'integer',
            ],
            'after_quantity' => [
                'required',
                'integer',
            ],
            'source' => [
                'required',
                'in:ECOMMERCE, WMS'
            ],
            'updated_by' => [
                'required',
            ]
        ];
    }
}
