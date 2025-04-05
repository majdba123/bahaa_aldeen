<?php

namespace App\Http\Requests\inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductModel;

class UpdateProductModelRequest extends FormRequest
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
        $productId = $this->route('id'); // الحصول على ID من الرابط

        return [
            'inventory_id' => 'sometimes|required|exists:inventories,id',
            'name' => 'sometimes|required|string|max:255',
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('product_models')->ignore($productId),
            ],
            'price' => 'sometimes|required|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'quantity' => 'sometimes|required|integer|min:0',
            'type' => 'sometimes|required|in:evening,wedding,engagement,party',
            'operation_type' => 'sometimes|required|in:rent,sale',
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
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
