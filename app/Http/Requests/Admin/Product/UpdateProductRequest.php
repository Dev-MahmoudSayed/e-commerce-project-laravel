<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|mimes:png,jpg,jpeg,Webp|max:5120',
            'price'=>'nullable|numeric|min:0',
            'attribute_ids' => 'required|array',
            'attribute_ids.*' => 'required|numeric|min:1',
            "category_id"=>"required|exists:category,id",
        ];
    }
}
