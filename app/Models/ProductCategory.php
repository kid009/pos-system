<?php

namespace App\Models;

use App\Models\ProductMainCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'product_main_category_id',
        'name',
        'created_by',
        'updated_by',
    ];

    public function productMainCategory()
    {
        return $this->belongsTo(ProductMainCategory::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
