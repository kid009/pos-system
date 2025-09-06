<?php

namespace App\Models;

use App\Models\Tenant;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;

class ProductMainCategory extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'created_by',
        'updated_by',
    ];

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
}
