<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $supplier = $this->route('supplier');

        if ($supplier) {
            // Update
            return $this->user('api')->can('update', $supplier);
        }

        // Create
        return $this->user('api')->can('create', Supplier::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }
}
