<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockCreateRequest extends FormRequest
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
        $routeName = $this->route()->getName();

        return [
            'product_code' => [
                'required',
                'integer',
            ],
            'quantity' => [
                'required',
                'integer',
            ]
        ];
    }
}
