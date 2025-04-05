<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProfile;
use Illuminate\Http\Request;

use App\Http\Requests\Employee\profile\EmployeeProfileStoreRequest; // استدعاء ملف الطلب
use App\Http\Requests\Employee\profile\EmployeeProfileUpdateRequest; // استدعاء ملف الطلب

use App\Services\Employee\EmployeeService; // استدعاء ملف الخدمة

class EmployeeProfileController extends Controller
{
    protected $employeeService;

    // الربط بين الكنترولر والخدمة
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;

    }



    public function store(EmployeeProfileStoreRequest $request)
    {
        $data = $request->validated();
        $employeeProfile = $this->employeeService->create_profile($data);
        return response()->json($employeeProfile, 201);
    }

    public function show($employeeId)
    {
        $employeeProfile = EmployeeProfile::where('employees_id', $employeeId)->firstOrFail();
        return response()->json($employeeProfile, 200);
    }

    public function update(EmployeeProfileUpdateRequest $request, $employeeId)
    {
        $employeeProfile = EmployeeProfile::where('employees_id', $employeeId)->firstOrFail(); // البحث عن الموديل باستخدام employee_id
        $data = $request->validated();
        $this->employeeService->update_profile($employeeProfile, $data); // إرسال الموديل إلى الخدمة
        return response()->json($employeeProfile, 200);
    }

    public function destroy($employeeId)
    {
        $employeeProfile = EmployeeProfile::where('employees_id', $employeeId)->firstOrFail(); // البحث عن الموديل باستخدام employee_id
        $this->employeeService->delete_profile($employeeProfile); // إرسال الموديل إلى الخدمة
        return response()->json(null, 204);
    }

}
