<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\ExpenseCategory;
use App\Models\ProductCategory;
use App\Models\ProductMainCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'domain',
        'status',
        'logo_path',
        'receipt_header_text',
        'receipt_footer_text',
    ];

    //1 Tenant มีได้หลาย Branch
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function mainCategory()
    {
        return $this->hasMany(ProductMainCategory::class);
    }
    
    public function category()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
