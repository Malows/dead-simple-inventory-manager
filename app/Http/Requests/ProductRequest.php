<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
            'code' => ['nullable'],
            'description' => ['nullable', 'string'],
            'min_stock_warning' => ['nullable', 'integer'],
            'name' => ['required'],
            'stock' => ['required', 'integer'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ];
    }
}
