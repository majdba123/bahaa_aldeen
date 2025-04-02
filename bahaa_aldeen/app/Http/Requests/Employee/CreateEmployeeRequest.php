<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // السماح باستخدام الريكويست
    }

    public function rules()
    {
        return [
            // التحقق من بيانات المستخدم
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',

            // التحقق من بيانات الموظف
            'branch_id' => 'required|exists:branches,id',
            'job_id' => 'required|exists:jobs,id',
            'national_id' => 'required|string|max:50|unique:employees,national_id',
            'nationality' => 'required|string|max:50',
            'gender' => 'required|string|in:male,female',
            'passport_number' => 'required|string|max:50|unique:employees,passport_number',
            'religion' => 'required|string|max:50',
            'military_status' => 'required|string|max:50',
            'insurance_number' => 'required|string|max:50|unique:employees,insurance_number',
            'marital_status' => 'required|string|max:50',
            'birthday' => 'required|date',

            'working_hours_from' => 'required|date_format:H:i', // وقت العمل من
            'working_hours_to' => 'required|date_format:H:i|after:working_hours_from', // وقت العمل إلى
            'commission_type' => 'nullable|string|in:salary,percentage', // نوع العمولة
            'commission_value' => 'nullable|numeric|required_if:commission_type,percentage|min:0|max:100', // نسبة العمولة
            'salary' => 'nullable|numeric|required_if:commission_type,salary|min:0', // الراتب
        ];
    }

    /**
     * الرسائل المخصصة للأخطاء.
     */
    public function messages()
    {
        return [
            // رسائل خاصة ببيانات المستخدم
            'name.required' => 'اسم الموظف مطلوب.',
            'name.string' => 'اسم الموظف يجب أن يكون نصياً.',
            'name.max' => 'اسم الموظف يجب ألا يزيد عن 255 حرفاً.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',

            // رسائل خاصة ببيانات الموظف
            'branch_id.required' => 'رقم الفرع مطلوب.',
            'branch_id.exists' => 'الفرع المحدد غير موجود.',
            'job_id.required' => 'رقم الوظيفة مطلوب.',
            'job_id.exists' => 'الوظيفة المحددة غير موجودة.',
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل.',
            'national_id.max' => 'رقم الهوية يجب ألا يزيد عن 50 حرفاً.',
            'nationality.required' => 'الجنسية مطلوبة.',
            'nationality.max' => 'الجنسية يجب ألا تزيد عن 50 حرفاً.',
            'gender.required' => 'الجنس مطلوب.',
            'gender.in' => 'الجنس يجب أن يكون إما male أو female.',
            'passport_number.required' => 'رقم جواز السفر مطلوب.',
            'passport_number.max' => 'رقم جواز السفر يجب ألا يزيد عن 50 حرفاً.',
            'passport_number.unique' => 'رقم جواز  السفر مستخدم بالفعل.',

            'religion.required' => 'الديانة مطلوبة.',
            'religion.max' => 'الديانة يجب ألا تزيد عن 50 حرفاً.',
            'military_status.required' => 'حالة التجنيد مطلوبة.',
            'military_status.max' => 'حالة التجنيد يجب ألا تزيد عن 50 حرفاً.',
            'insurance_number.required' => 'الرقم التأميني مطلوب.',
            'insurance_number.max' => 'الرقم التأميني يجب ألا يزيد عن 50 حرفاً.',
            'insurance_number.unique' => 'رقم التأمين مستخدم بالفعل.',

            'marital_status.required' => 'الحالة الاجتماعية مطلوبة.',
            'marital_status.max' => 'الحالة الاجتماعية يجب ألا تزيد عن 50 حرفاً.',
            'birthday.required' => 'تاريخ الميلاد مطلوب.',
            'birthday.date' => 'يجب أن يكون تاريخ الميلاد صالحاً.',


            'working_hours_from.required' => 'وقت العمل من مطلوب.',
            'working_hours_from.date_format' => 'وقت العمل من يجب أن يكون بتنسيق HH:mm.',
            'working_hours_to.required' => 'وقت العمل إلى مطلوب.',
            'working_hours_to.date_format' => 'وقت العمل إلى يجب أن يكون بتنسيق HH:mm.',
            'working_hours_to.after' => 'وقت العمل إلى يجب أن يكون بعد وقت العمل من.',
            'commission_type.in' => 'نوع العمولة يجب أن يكون salary أو percentage فقط.',
            'commission_value.required_if' => 'قيمة العمولة مطلوبة عند اختيار النوع percentage.',
            'commission_value.numeric' => 'قيمة العمولة يجب أن تكون رقماً.',
            'commission_value.min' => 'قيمة العمولة يجب ألا تقل عن 0.',
            'commission_value.max' => 'قيمة العمولة يجب ألا تزيد عن 100.',
            'salary.required_if' => 'الراتب مطلوب عند اختيار النوع salary.',
            'salary.numeric' => 'الراتب يجب أن يكون رقماً.',
            'salary.min' => 'الراتب يجب ألا يقل عن 0.',
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
