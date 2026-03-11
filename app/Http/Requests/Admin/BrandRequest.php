<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
        $brandId = $this->route('brand');

        return [
            'name' => 'required|string|max:255|unique:brands,name,' . $brandId,
            'logo' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم الماركة مطلوب',
            'name.unique' => 'اسم الماركة موجود مسبقاً',
            'logo.image' => 'يجب أن يكون الشعار صورة',
            'logo.max' => 'حجم الشعار يجب ألا يتجاوز 2 ميجابايت',
        ];
    }
}
