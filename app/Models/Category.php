<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\MainCategory;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasUserActivity;

    protected $fillable = [
        'main_category_id',
        'name',
        'created_by',
        'updated_by'
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relations
    public function products() {
        return $this->hasMany(Product::class);
    }
}
