<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserActivity;

class Product extends Model
{
    use HasFactory, HasUserActivity;

    protected $fillable = [
        'category_id',
        'name',
        'barcode',
        'cost',
        'price',
        'stock_qty',
        'image_path',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
