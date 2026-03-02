<?php

namespace App\Models;

use App\Models\Category;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUserActivity, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'cost',
        'price',
        'stock_qty',
        'image_path',
        'is_active',
        'created_by',
        'updated_by',
    ];

    const UNTRACKED_CATEGORIES = [
        'น้ำแก๊ส',
    ];

    public function idTrackingStock(): bool
    {
        // แนะนำเปลี่ยนไปใช้ -> return $this->is_tracking_stock; ในอนาคต
        return !in_array($this->name, self::UNTRACKED_CATEGORIES);
    }

    // เชื่อมไปหา Category
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
