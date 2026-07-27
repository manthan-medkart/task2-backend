<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ecommerce_order_id' => [
                'required',
                'integer',
                'unique:sales_orders,ecommerce_order_id',
            ],
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],
            'customer_email' => [
                'required',
                'email',
            ],
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.product_code' => [
                'required',
                'integer',
                'exists:products,product_code',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
