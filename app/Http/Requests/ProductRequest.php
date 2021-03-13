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
        $null = 'nullable';

        return [
            'categories' => ['array'],
            'categories.*' => ['exists:categories,id'],
            'code' => [$null],
            'description' => [$null, 'string'],
            'min_stock_warning' => [$null, 'integer'],
            'name' => ['required'],
            'price' => [$null, 'numeric'],
            'stock' => ['required', 'integer'],
            'supplier_id' => [$null, 'exists:suppliers,id'],
        ];
    }
}
