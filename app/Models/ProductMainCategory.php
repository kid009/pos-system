<?php

namespace App\Models;

use App\Models\Tenant;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductMainCategory extends Model
{
    use HasFactory;
    
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
