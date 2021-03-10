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
            'name' => ['required'],
            'code' => ['nullable'],
            'description' => ['nullable', 'string'],
            'stock' => ['required', 'integer'],
            'min_stock_warning' => ['required', 'integer'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
        ];
    }
}
