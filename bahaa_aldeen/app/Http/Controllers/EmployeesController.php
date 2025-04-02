<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use App\Http\Requests\Employee\CreateEmployeeRequest; // استدعاء ملف الطلب
use App\Http\Requests\Employee\UpdateEmployeeRequest; // استدعاء ملف الطلب

use App\Services\Employee\EmployeeService; // استدعاء ملف الخدمة
use Illuminate\Http\JsonResponse;

class EmployeesController extends Controller
{
    protected $employeeService;

    // الربط بين الكنترولر والخدمة
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;

    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default to 10 records per page if not specified
        $employees = $this->employeeService->getAllEmployeesWithDetails($perPage);

        return response()->json($employees);
    }


    public function show($id)
    {
        $employees = $this->employeeService->showEmployee($id);

        return response()->json($employees);
    }



    public function store(CreateEmployeeRequest $request): JsonResponse
    {
        try {
            // استدعاء دالة الخدمة لإنشاء الموظف
            $employee = $this->employeeService->createEmployee($request->validated());

            return response()->json([
                'message' => 'Employee created successfully.',
                'employee' => $employee,
            ], 201);
        } catch (\Exception $e) {
            // التعامل مع الأخطاء غير المتوقعة
            return response()->json([
                'message' => 'An error occurred while creating the employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(UpdateEmployeeRequest $request, $employee_id): JsonResponse
    {
        $employee=Employees::findorfail($employee_id);
        try {
            // استدعاء الخدمة لتحديث بيانات الموظف
            $updatedEmployee = $this->employeeService->updateEmployee($request->validated(), $employee);

            return response()->json([
                'message' => 'Employee updated successfully.',
                'employee' => $updatedEmployee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the employee.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:active,dismissed,on_leave',
        ]);

        $employee = Employees::findOrFail($id);
        $updatedEmployee = $this->employeeService->updateEmployeeStatus($request->status, $employee);

        return response()->json([
            'message' => 'Employee status updated successfully.',
            'employee' => $updatedEmployee,
        ]);
    }

}
