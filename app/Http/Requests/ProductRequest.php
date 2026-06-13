<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id'   => ['required', 'exists:categories,id'],
            'name'          => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'capital_price' => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'status'        => ['required', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])],
        ];
    }
}
