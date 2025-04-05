<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;
        protected $fillable = [
        'employees_id',
        'college',
        'major',
        'university',
        'graduation_year',
        'governorate',
        'city',
        'village',
        'landline_phone',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}
