<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',         // معرف الموظف
        'working_hours_from',  // وقت العمل (من)
        'working_hours_to',    // وقت العمل (إلى)
        'commission_type',     // نوع العمولة
        'commission_value',    // قيمة العمولة (نسبة)
        'salary',              // الراتب
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
