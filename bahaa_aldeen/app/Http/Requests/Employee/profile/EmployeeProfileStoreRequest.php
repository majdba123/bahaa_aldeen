<?php

namespace App\Http\Requests\Employee\profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class EmployeeProfileStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'college' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'graduation_year' => 'required|integer|min:1900|max:' . date('Y'),
            'governorate' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'village' => 'nullable|string|max:255',
            'landline_phone' => 'nullable|string|max:15|unique:employee_profiles,landline_phone', // جعل الهاتف الأرضي فريدًا
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'معرف الموظف مطلوب.',
            'employee_id.exists' => 'معرف الموظف غير موجود.',
            'college.required' => 'اسم الكلية مطلوب.',
            'college.string' => 'اسم الكلية يجب أن يكون نصيًا.',
            'college.max' => 'اسم الكلية يجب ألا يزيد عن 255 حرفًا.',
            'major.required' => 'التخصص مطلوب.',
            'major.string' => 'التخصص يجب أن يكون نصيًا.',
            'major.max' => 'التخصص يجب ألا يزيد عن 255 حرفًا.',
            'university.required' => 'اسم الجامعة مطلوب.',
            'university.string' => 'اسم الجامعة يجب أن يكون نصيًا.',
            'university.max' => 'اسم الجامعة يجب ألا يزيد عن 255 حرفًا.',
            'graduation_year.required' => 'سنة التخرج مطلوبة.',
            'graduation_year.integer' => 'سنة التخرج يجب أن تكون رقمًا صحيحًا.',
            'graduation_year.min' => 'سنة التخرج يجب ألا تقل عن 1900.',
            'graduation_year.max' => 'سنة التخرج يجب ألا تزيد عن السنة الحالية.',
            'governorate.required' => 'المحافظة مطلوبة.',
            'governorate.string' => 'المحافظة يجب أن تكون نصًا.',
            'governorate.max' => 'المحافظة يجب ألا تزيد عن 255 حرفًا.',
            'city.required' => 'المدينة مطلوبة.',
            'city.string' => 'المدينة يجب أن تكون نصًا.',
            'city.max' => 'المدينة يجب ألا تزيد عن 255 حرفًا.',
            'village.string' => 'القرية يجب أن تكون نصًا.',
            'village.max' => 'القرية يجب ألا تزيد عن 255 حرفًا.',
            'landline_phone.string' => 'رقم الهاتف الأرضي يجب أن يكون نصًا.',
            'landline_phone.max' => 'رقم الهاتف الأرضي يجب ألا يزيد عن 15 حرفًا.',
            'landline_phone.unique' => 'رقم الهاتف الأرضي مستخدم بالفعل.', // الرسالة المخصصة
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }

}
