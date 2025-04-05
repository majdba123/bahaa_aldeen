<?php

namespace App\Http\Requests\inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreProductModelRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'inventory_id' => 'required|exists:inventories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:product_models,code',
            'price' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'type' => 'required|in:evening,wedding,engagement,party',
            'operation_type' => 'required|in:rent,sale',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'inventory_id.required' => 'يجب اختيار المخزون',
            'name.required' => 'اسم الموديل مطلوب',
            'code.unique' => 'كود الموديل مستخدم مسبقاً',
            'type.in' => 'نوع الفستان غير صحيح',
            'operation_type.in' => 'نوع العملية غير صحيح',
            'images.*.image' => 'يجب أن تكون الملفات صوراً',
            'images.*.max' => 'حجم الصورة يجب أن لا يتجاوز 2MB',
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
