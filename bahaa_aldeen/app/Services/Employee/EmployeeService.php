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

            // إضافة بيانات الموظف إلى الموديل
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

            return $employee;
        });
    }

    public function updateEmployee(array $data, Employees $employee)
    {
        return DB::transaction(function () use ($data, $employee) {
            // تحديث بيانات المستخدم (User)
            if (isset($data['email']) || isset($data['password']) || isset($data['name'])) {
                $user = $employee->user;

                if (isset($data['email'])) {
                    $user->email = $data['email'];
                }

                if (isset($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }

                if (isset($data['name'])) {
                    $user->name = $data['name'];
                }

                $user->save();
            }

            // تحديث بيانات الموظف (Employee)
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

            // تحديث تفاصيل التوظيف (EmploymentDetails)
            if ($employee->employmentDetails) {
                $employee->employmentDetails->update([
                    'working_hours_from' => $data['working_hours_from'] ?? $employee->employmentDetails->working_hours_from,
                    'working_hours_to' => $data['working_hours_to'] ?? $employee->employmentDetails->working_hours_to,
                    'commission_type' => $data['commission_type'] ?? $employee->employmentDetails->commission_type,
                    'commission_value' => $data['commission_value'] ?? $employee->employmentDetails->commission_value,
                    'salary' => $data['salary'] ?? $employee->employmentDetails->salary,
                ]);
            }

            return $employee;
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
