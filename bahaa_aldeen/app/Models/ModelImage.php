<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_model_id',
        'path',

    ];

    public function model()
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id');
    }
}
