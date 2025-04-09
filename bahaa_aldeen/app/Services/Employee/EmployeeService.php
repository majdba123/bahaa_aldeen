<?php

namespace App\Services\Employee;

use App\Models\EmployeeProfile;
use App\Models\Employees;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    public function createEmployee(array $data)
    {
        return DB::transaction(function () use ($data) {
            // إنشاء حساب المستخدم
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // إضافة بيانات الموظف
            $employee = Employees::create([
                'user_id' => $user->id,
                'branch_id' => $data['branch_id'],
                'job_id' => $data['job_id'],
                'national_id' => $data['national_id'],
                'nationality' => $data['nationality'],
                'gender' => $data['gender'],
                'passport_number' => $data['passport_number'] ?? null,
                'religion' => $data['religion'] ?? null,
                'military_status' => $data['military_status'] ?? null,
                'insurance_number' => $data['insurance_number'] ?? null,
                'marital_status' => $data['marital_status'] ?? null,
                'birthday' => $data['birthday'],
            ]);

            // إضافة بيانات التوظيف
            $employee->employmentDetails()->create([
                'working_hours_from' => $data['working_hours_from'],
                'working_hours_to' => $data['working_hours_to'],
                'commission_type' => $data['commission_type'] ?? null,
                'commission_value' => $data['commission_value'] ?? null,
                'salary' => $data['salary'] ?? null,
            ]);

            // جلب البيانات المحدثة مع العلاقات
            return Employees::with(['user', 'employmentDetails'])->find($employee->id);
        });
    }

    public function updateEmployee(array $data, Employees $employee)
    {
        return DB::transaction(function () use ($data, $employee) {
            // تحديث بيانات المستخدم
            $userData = array_filter([
                'email' => $data['email'] ?? null,
                'password' => isset($data['password']) ? Hash::make($data['password']) : null,
                'name' => $data['name'] ?? null
            ], function($value) {
                return $value !== null;
            });

            if (!empty($userData)) {
                $employee->user()->update($userData);
            }

            // تحديث بيانات الموظف
            $employee->update([
                'branch_id' => $data['branch_id'] ?? $employee->branch_id,
                'job_id' => $data['job_id'] ?? $employee->job_id,
                'national_id' => $data['national_id'] ?? $employee->national_id,
                'nationality' => $data['nationality'] ?? $employee->nationality,
                'gender' => $data['gender'] ?? $employee->gender,
                'passport_number' => $data['passport_number'] ?? $employee->passport_number,
                'religion' => $data['religion'] ?? $employee->religion,
                'military_status' => $data['military_status'] ?? $employee->military_status,
                'insurance_number' => $data['insurance_number'] ?? $employee->insurance_number,
                'marital_status' => $data['marital_status'] ?? $employee->marital_status,
                'birthday' => $data['birthday'] ?? $employee->birthday,
            ]);

            // تحديث أو إنشاء تفاصيل التوظيف
            $employmentDetailsData = [
                'working_hours_from' => $data['working_hours_from'] ?? $employee->employmentDetails->working_hours_from ?? null,
                'working_hours_to' => $data['working_hours_to'] ?? $employee->employmentDetails->working_hours_to ?? null,
                'commission_type' => $data['commission_type'] ?? $employee->employmentDetails->commission_type ?? null,
                'commission_value' => $data['commission_value'] ?? $employee->employmentDetails->commission_value ?? null,
                'salary' => $data['salary'] ?? $employee->employmentDetails->salary ?? null,
            ];

            if ($employee->employmentDetails) {
                $employee->employmentDetails()->update($employmentDetailsData);
            } else {
                $employee->employmentDetails()->create($employmentDetailsData);
            }

            // جلب البيانات المحدثة مع العلاقات بنفس طريقة الإنشاء
            return Employees::with(['user', 'employmentDetails'])->find($employee->id);
        });
    }

    public function getAllEmployeesWithDetails(int $perPage = 10)
    {
        return Employees::with(['user', 'job','employmentDetails'])
            ->paginate($perPage);
    }

    public function showEmployee(int $employeeId)
    {
        return Employees::with(['user', 'job', 'employmentDetails'])
            ->findOrFail($employeeId); // Will throw a 404 error if the employee is not found
    }


    public function updateEmployeeStatus(Employees $employee, string $status)
    {
        if (!in_array($status, ['active', 'dismissed', 'on_leave'])) {
            throw new \InvalidArgumentException('Invalid status provided.');
        }

        $employee->status = $status;
        $employee->save();

        return $employee;
    }


    public function create_profile(array $data)
    {
        return EmployeeProfile::create($data);
    }
    public function update_profile(EmployeeProfile $employeeProfile, array $data)
    {
        return $employeeProfile->update($data);
    }

    public function delete_profile(EmployeeProfile $employeeProfile)
    {
        return $employeeProfile->delete();
    }

}
