<?php

namespace App\Services\Branch;

use App\Models\Branches;

class BranchService
{
    public function createBranch($data)
    {
        return Branches::create($data);
    }

    public function updateBranch($data, $id)
    {
        $branch = Branches::findOrFail($id);
        $branch->update($data);
        return $branch;
    }

    public function deleteBranch($id)
    {
        $branch = Branches::findOrFail($id);
        $branch->delete();
    }

    public function getAllBranches($perPage = 10)
    {
        return Branches::paginate($perPage); // إرجاع البيانات مع التصفح
    }

    public function getBranchById($id)
    {
        return Branches::findOrFail($id);
    }



    public function filterBranches($filters, $perPage = 10)
    {
        $query = Branches::query();

        // تطبيق الفلاتر إذا كانت موجودة
        if (!empty($filters['branch_name'])) {
            $query->where('branch_name', 'LIKE', '%' . $filters['branch_name'] . '%');
        }

        if (!empty($filters['branch_number'])) {
            $query->where('branch_number', $filters['branch_number']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'LIKE', '%' . $filters['location'] . '%');
        }

        // إرجاع النتائج مع التصفح (Pagination)
        return $query->paginate($perPage);
    }
}
