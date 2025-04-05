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


    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    protected static function booted()
    {
        static::created(function ($branch) {
            // إنشاء مخزون تلقائي عند إنشاء فرع جديد
            $branch->inventory()->create();
        });
    }
}
