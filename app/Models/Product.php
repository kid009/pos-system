<?php

namespace App\Models;

use App\Models\Tenant;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'product_category_id',
        'name',
        'sku',
        'description',
        'image',
        'cost',
        'price',
        'created_by',
        'updated_by',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
