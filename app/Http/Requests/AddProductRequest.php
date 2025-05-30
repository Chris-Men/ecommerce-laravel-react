<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('products')->ignore($productId)
            ],
            'qty' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'required|string|max:5000',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'A product with this name already exists',
            'color_id.required' => 'The color field is required',
            'color_id.exists' => 'The selected color is invalid',
            'size_id.required' => 'The size field is required',
            'size_id.exists' => 'The selected size is invalid',
            'category_id.required' => 'The category field is required',
            'category_id.exists' => 'The selected category is invalid',
            'brand_id.required' => 'The brand field is required',
            'brand_id.exists' => 'The selected brand is invalid',
            'description.required' => 'The description field is required',
            'description.max' => 'The description field must not be greater than :max characters',
            'qty.required' => 'The quantity field is required',
            'qty.min' => 'The quantity must be at least 0',
            'price.required' => 'The price field is required',
            'price.min' => 'The price must be at least 0',

            'image.image' => 'The image must be an image',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, webp',

        ];
    }
}
