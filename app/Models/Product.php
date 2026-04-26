<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'cost',
        'stock_qty',
        'unit',
        'image',
        'affiliate_link', // 🌟 อนุญาตให้บันทึกลิงก์
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    // ผูกความสัมพันธ์ 1 สินค้า อยู่ใน 1 หมวดหมู่ (Belongs To)
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Master Data: Product');
    }
}
