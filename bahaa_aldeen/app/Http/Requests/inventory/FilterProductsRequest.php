<?php

namespace App\Http\Requests\inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class FilterProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inventory_ids' => 'nullable|array',
            'inventory_ids.*' => 'exists:inventories,id',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
            'types' => 'nullable|array',
            'types.*' => 'in:evening,wedding,engagement,party',
            'operation_types' => 'nullable|array',
            'operation_types.*' => 'in:rent,sale',
            // حقول البحث المنفصلة
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'code' => 'nullable|string|max:50',
            // فلاتر أخرى
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:0',
            'sort_by' => 'nullable|in:latest,price_asc,price_desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'inventory_ids.*.exists' => 'معرف مخزن غير صحيح',
            'branch_ids.*.exists' => 'معرف فرع غير صحيح',
            'types.*.in' => 'نوع المنتج غير صحيح',
            'operation_types.*.in' => 'نوع العملية غير صحيح',
            'sort_by.in' => 'خيار التصنيف غير صحيح',
            'name.max' => 'يجب ألا يتجاوز اسم المنتج 255 حرفًا',
            'description.max' => 'يجب ألا يتجاوز الوصف 1000 حرف',
            'code.max' => 'يجب ألا يتجاوز الكود 50 حرفًا',
            'min_price.min' => 'يجب أن يكون الحد الأدنى للسعر رقمًا موجبًا',
            'max_price.min' => 'يجب أن يكون الحد الأقصى للسعر رقمًا موجبًا',
            'min_quantity.min' => 'يجب أن تكون الكمية الحد الأدنى رقمًا موجبًا',
            'per_page.min' => 'يجب أن يكون عدد العناصر في الصفحة على الأقل 1',
            'per_page.max' => 'يجب ألا يتجاوز عدد العناصر في الصفحة 100',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        // تخصيص رسالة الخطأ
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
