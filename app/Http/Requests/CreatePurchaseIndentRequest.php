<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseIndentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sales_indent_ids' => 'required|array|min:1',
            'sales_indent_ids.*' => 'required|integer|exists:sales_indents,id',
        ];
    }

    public function messages(): array
    {
        return [
            'sales_indent_ids.required' => 'At least one sales indent ID is required.',
            'sales_indent_ids.array' => 'Sales indent IDs must be an array.',
            'sales_indent_ids.min' => 'At least one sales indent ID is required.',
            'sales_indent_ids.*.exists' => 'Sales indent ID :input does not exist.',
        ];
    }
}
