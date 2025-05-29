<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductRequest extends FormRequest
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

            'thumbnail' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'first_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'second_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'third_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
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

            'thumbnail.image' => 'The thumbnail must be an image',
            'thumbnail.mimes' => 'The thumbnail must be a file of type: png, jpg, jpeg, webp',
            'thumbnail.max' => 'The thumbnail may not be greater than 2MB',

            'first_image.image' => 'The first image must be an image',
            'first_image.mimes' => 'The first image must be a file of type: png, jpg, jpeg, webp',
            'first_image.max' => 'The first image may not be greater than 2MB',

            'second_image.image' => 'The second image must be an image',
            'second_image.mimes' => 'The second image must be a file of type: png, jpg, jpeg, webp',
            'second_image.max' => 'The second image may not be greater than 2MB',

            'third_image.image' => 'The third image must be an image',
            'third_image.mimes' => 'The third image must be a file of type: png, jpg, jpeg, webp',
            'third_image.max' => 'The third image may not be greater than 2MB',
        ];
    }
}
