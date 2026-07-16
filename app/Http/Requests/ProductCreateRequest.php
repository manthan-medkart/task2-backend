<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'composition' => [
                'required',
                'max:255',
            ],
            'mrp' => [
                'required',
                'numeric',
            ],
            'sales_rate' => [
                'required',
                'numeric',
            ],
            'total_strip' => [
                'required',
                'numeric',
            ],
            'medicine_per_strip' => [
                'required',
                'numeric',
            ],
            'image_url' => [
                'required',
                'string',
            ]

        ];
    }
}
