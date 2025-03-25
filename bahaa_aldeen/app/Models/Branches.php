<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_name',
        'branch_number',
        'phone',
        'location',
    ];


    public function employees()
    {
        return $this->hasMany(Employees::class);
    }
}
