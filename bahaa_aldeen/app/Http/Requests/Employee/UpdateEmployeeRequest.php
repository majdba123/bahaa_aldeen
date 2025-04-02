<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // السماح باستخدام الريكويست
    }

    public function rules()
    {
        $employee = $this->route('employee'); // الحصول على كائن الموظف من المسار

        return [
            'branch_id' => 'sometimes|exists:branches,id',
            'job_id' => 'sometimes|exists:jobs,id',
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($employee ? $employee->user_id : null),
            ],
            'password' => 'sometimes|string|min:6|confirmed',
            'national_id' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('employees', 'national_id')->ignore($employee ? $employee->id : null),
            ],
            'passport_number' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('employees', 'passport_number')->ignore($employee ? $employee->id : null),
            ],
            'nationality' => 'sometimes|string|max:50',
            'gender' => 'sometimes|string|in:male,female',
            'religion' => 'sometimes|string|max:50',
            'military_status' => 'sometimes|string|max:50',
            'insurance_number' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('employees', 'insurance_number')->ignore($employee ? $employee->id : null),
            ],
            'marital_status' => 'sometimes|string|max:50',
            'birthday' => 'sometimes|date',
            'working_hours_from' => 'sometimes|date_format:H:i',
            'working_hours_to' => 'sometimes|date_format:H:i|after:working_hours_from',
            'commission_type' => 'nullable|string|in:salary,percentage',
            'commission_value' => 'nullable|numeric|required_if:commission_type,percentage|min:0|max:100',
            'salary' => 'nullable|numeric|required_if:commission_type,salary|min:0',
        ];
    }

    public function messages()
    {
        return [
            'branch_id.exists' => 'رقم الفرع غير موجود.',
            'job_id.exists' => 'رقم الوظيفة غير موجود.',
            'name.string' => 'اسم الموظف يجب أن يكون نصياً.',
            'name.max' => 'اسم الموظف يجب ألا يزيد عن 255 حرفاً.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل.',
            'passport_number.unique' => 'رقم جواز السفر مستخدم بالفعل.',
            'national_id.max' => 'رقم الهوية يجب ألا يزيد عن 50 حرفاً.',
            'gender.in' => 'الجنس يجب أن يكون إما male أو female.',
            'birthday.date' => 'تاريخ الميلاد يجب أن يكون صالحاً.',
            'working_hours_from.date_format' => 'وقت العمل من يجب أن يكون بتنسيق HH:mm.',
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
