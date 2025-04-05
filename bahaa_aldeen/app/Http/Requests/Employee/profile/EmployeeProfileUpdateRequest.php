<?php

namespace App\Http\Requests\Employee\profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'college' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'governorate' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'landline_phone' => 'nullable|string|max:15|unique:employee_profiles,landline_phone,' . $this->route('id'), // الفريد فقط إذا تم تعديله
        ];
    }

    public function messages()
    {
        return [
            'college.string' => 'اسم الكلية يجب أن يكون نصيًا.',
            'college.max' => 'اسم الكلية يجب ألا يزيد عن 255 حرفًا.',
            'major.string' => 'التخصص يجب أن يكون نصيًا.',
            'major.max' => 'التخصص يجب ألا يزيد عن 255 حرفًا.',
            'university.string' => 'اسم الجامعة يجب أن يكون نصيًا.',
            'university.max' => 'اسم الجامعة يجب ألا يزيد عن 255 حرفًا.',
            'graduation_year.integer' => 'سنة التخرج يجب أن تكون رقمًا صحيحًا.',
            'graduation_year.min' => 'سنة التخرج يجب ألا تقل عن 1900.',
            'graduation_year.max' => 'سنة التخرج يجب ألا تزيد عن السنة الحالية.',
            'governorate.string' => 'المحافظة يجب أن تكون نصًا.',
            'governorate.max' => 'المحافظة يجب ألا تزيد عن 255 حرفًا.',
            'city.string' => 'المدينة يجب أن تكون نصًا.',
            'city.max' => 'المدينة يجب ألا تزيد عن 255 حرفًا.',
            'village.string' => 'القرية يجب أن تكون نصًا.',
            'village.max' => 'القرية يجب ألا تزيد عن 255 حرفًا.',
            'landline_phone.string' => 'رقم الهاتف الأرضي يجب أن يكون نصًا.',
            'landline_phone.max' => 'رقم الهاتف الأرضي يجب ألا يزيد عن 15 حرفًا.',
            'landline_phone.unique' => 'رقم الهاتف الأرضي مستخدم بالفعل.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
