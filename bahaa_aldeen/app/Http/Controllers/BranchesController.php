<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use Illuminate\Http\Request;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use Illuminate\Support\Facades\Validator;

use App\Services\Branch\BranchService;

use Illuminate\Support\Facades\Validator as FacadesValidator;

class BranchesController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index()
    {
        $branches = $this->branchService->getAllBranches();
        return response()->json($branches);
    }

    public function show($id)
    {
        $branch = $this->branchService->getBranchById($id);
        return response()->json($branch);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->createBranch($request->validated());
        return response()->json($branch, 201);
    }

    public function update(UpdateBranchRequest $request, $id)
    {
        $branch = $this->branchService->updateBranch($request->validated(), $id);
        return response()->json($branch);
    }

    public function destroy($id)
    {
        $this->branchService->deleteBranch($id);
        return response()->json(['message' => 'Branch deleted successfully']);
    }

    public function filter(Request $request)
    {
        // التحقق من صحة البيانات باستخدام FacadesValidator
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'branch_name' => 'nullable|string|max:255',
            'branch_number' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
        ], [
            'branch_name.string' => 'اسم الفرع يجب أن يكون نصاً.',
            'branch_name.max' => 'اسم الفرع يجب ألا يزيد عن 255 حرفاً.',
            'branch_number.integer' => 'رقم الفرع يجب أن يكون رقماً صحيحاً.',
            'location.string' => 'الموقع يجب أن يكون نصاً.',
            'location.max' => 'الموقع يجب ألا يزيد عن 255 حرفاً.',
        ]);

        // التحقق من وجود أخطاء
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // الفلاتر (القيم التي تم إرسالها فقط)
        $filters = array_filter($validator->validated(), function ($value) {
            return !is_null($value); // استبعاد القيم الفارغة
        });

        // التحقق إذا لم يتم إرسال أي فلتر
        if (empty($filters)) {
            return response()->json(['error' => 'يجب إرسال قيمة واحدة على الأقل للفلترة.'], 422);
        }

        // جلب النتائج من الخدمة
        $branches = $this->branchService->filterBranches($filters);

        return response()->json($branches);
    }


}
