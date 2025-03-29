<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // تأكد أن المستخدم لديه صلاحية
    }

    public function rules()
    {
        return [
            'branch_name' => 'required|string|max:255',
            'branch_number' => 'required|integer|unique:branches,branch_number',
            'phone' => 'required|string|max:15',
            'location' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'branch_name.required' => 'اسم الفرع مطلوب.',
            'branch_name.string' => 'اسم الفرع يجب أن يكون نصياً.',
            'branch_name.max' => 'اسم الفرع يجب ألا يزيد عن 255 حرفاً.',
            'branch_number.required' => 'رقم الفرع مطلوب.',
            'branch_number.integer' => 'رقم الفرع يجب أن يكون رقماً صحيحاً.',
            'branch_number.unique' => 'رقم الفرع مستخدم بالفعل.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصياً.',
            'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 15 حرفاً.',
            'location.required' => 'العنوان مطلوب.',
            'location.string' => 'العنوان يجب أن يكون نصياً.',
            'location.max' => 'العنوان يجب ألا يزيد عن 255 حرفاً.',
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
