<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Auditable; // นำ Trait Auditable มาใช้ได้เลย (DRY)

    protected $fillable = [
        'shop_id',
        'name',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // สมมติว่าเรามี Model Product (เตรียมไว้สำหรับเช็ค Safe Delete และ withCount)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
