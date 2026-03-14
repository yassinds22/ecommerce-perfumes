<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product');

        return [
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'short_description.en' => 'nullable|string',
            'short_description.ar' => 'nullable|string',
            'price' => 'required|numeric|min:0|gt:purchase_price',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $productId,
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'gender' => 'required|in:Men,Women,Unisex,Kids',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'top_notes' => 'nullable|array',
            'top_notes.*' => 'exists:fragrance_notes,id',
            'middle_notes' => 'nullable|array',
            'middle_notes.*' => 'exists:fragrance_notes,id',
            'base_notes' => 'nullable|array',
            'base_notes.*' => 'exists:fragrance_notes,id',
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
            'category_id.required' => 'القسم مطلوب',
            'brand_id.required' => 'الماركة مطلوبة',
            'name.en.required' => 'اسم المنتج بالإنجليزية مطلوب',
            'name.ar.required' => 'اسم المنتج بالعربية مطلوب',
            'price.required' => 'سعر البيع مطلوب',
            'price.gt' => 'سعر البيع يجب أن يكون أكبر من سعر الشراء',
            'purchase_price.required' => 'سعر الشراء مطلوب',
            'sku.required' => 'رمز SKU مطلوب',
            'sku.unique' => 'رمز SKU موجود مسبقاً',
            'stock_quantity.required' => 'الكمية مطلوبة',
            'gender.required' => 'الجنس المستهدف مطلوب',
            'image.image' => 'يجب أن يكون الملف صورة',
        ];
    }
}
