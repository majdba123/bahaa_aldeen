<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'name',          // اسم الموديل
        'code',          // كود الموديل
        'price',         // السعر
        'size',         // المقاس
        'color',         // اللون
        'quantity',     // الكمية
        'type',
        'operation_type',
        'description',   // الوصف
    ];


    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function images()
    {
        return $this->hasMany(ModelImage::class);
    }
}
