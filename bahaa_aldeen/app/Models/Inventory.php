<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'branches_id',
    ];




    public function branch()
    {
        return $this->belongsTo(Branches::class ,'branches_id');
    }

    public function models()
    {
        return $this->hasMany(ProductModel::class);
    }

}
