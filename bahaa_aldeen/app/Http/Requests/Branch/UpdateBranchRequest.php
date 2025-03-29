<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBranchRequest extends FormRequest
{
    public function authorize()
    {
        return true; // تأكد أن المستخدم لديه صلاحية
    }

    /**
     * قواعد التحقق من البيانات.
     */
    public function rules()
    {
        return [
            'branch_name' => 'sometimes|string|max:255',
            'branch_number' => 'sometimes|integer|unique:branches,branch_number,' . $this->route('id'),
            'phone' => 'sometimes|string|max:15',
            'location' => 'sometimes|string|max:255',
        ];
    }

    /**
     * الرسائل المخصصة للأخطاء.
     */
    public function messages()
    {
        return [
            'branch_name.sometimes' => 'اسم الفرع مطلوب إذا تم إرساله.',
            'branch_name.string' => 'اسم الفرع يجب أن يكون نصياً.',
            'branch_name.max' => 'اسم الفرع يجب ألا يزيد عن 255 حرفاً.',
            'branch_number.sometimes' => 'رقم الفرع مطلوب إذا تم إرساله.',
            'branch_number.integer' => 'رقم الفرع يجب أن يكون رقماً صحيحاً.',
            'branch_number.unique' => 'رقم الفرع مستخدم بالفعل.',
            'phone.sometimes' => 'رقم الهاتف مطلوب إذا تم إرساله.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصياً.',
            'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 15 حرفاً.',
            'location.sometimes' => 'العنوان مطلوب إذا تم إرساله.',
            'location.string' => 'العنوان يجب أن يكون نصياً.',
            'location.max' => 'العنوان يجب ألا يزيد عن 255 حرفاً.',
        ];
    }

    /**
     * تخصيص رسالة الخطأ.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
