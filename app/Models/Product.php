<?php

namespace App\Models;

use App\Models\Category;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'updated_by',
    ];

    // เชื่อมไปหา Category
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // (Option) Accessor เพื่อเช็คว่าเป็นหมวดน้ำแก๊สหรือไม่
    // ช่วยให้เช็คใน View ง่ายขึ้น: $product->is_gas
    public function getIsGasAttribute()
    {
        return $this->category && $this->category->name === 'น้ำแก๊ส';
    }
}
