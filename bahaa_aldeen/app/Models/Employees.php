<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'job_id',
        'user_id',
        'national_id', // رقم الهوية
        'nationality', // الجنسية
        'gender', // الجنس
        'passport_number', // رقم جواز السفر
        'religion', // الديانة
        'military_status', // حالة التجنيد
        'insurance_number', // الرقم التأميني
        'marital_status', // الحالة الاجتماعية
        'status',
        'birthday',
    ];


    public function Branches()
    {
        return $this->belongsTo(Branches::class ,'branch_id');
    }

    public function job()
    {
        return $this->belongsTo(Jobs::class ,'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class ,'user_id');
    }

    public function employmentDetails()
    {
        return $this->hasOne(EmploymentDetails::class);
    }

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

}
