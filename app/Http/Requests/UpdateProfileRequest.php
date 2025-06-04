<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Asegura que cualquier usuario autenticado pueda usar esta request
    }

    public function rules()
    {
        return [
            'name'          => 'sometimes|string|max:255',
            'email'         => 'sometimes|email|max:255|unique:users,email,' . $this->user()->id,
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'country'       => 'nullable|string|max:255',
            'zip_code'      => 'nullable|string|max:10',
            'phone_number'  => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
