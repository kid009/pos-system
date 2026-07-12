<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
        'parent_id',
    ];

    protected $attributes = [
        'sort_order' => 0,
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // บันทึกเฉพาะฟิลด์ที่เราอนุญาตให้เติมข้อมูล
            ->logOnlyDirty() // บันทึกเฉพาะฟิลด์ที่มีการเปลี่ยนแปลงจริง ๆ ไม่บันทึกซ้ำซ้อน
            ->dontSubmitEmptyLogs(); // ถ้าไม่มีอะไรเปลี่ยน ไม่ต้องบันทึกให้เปลืองพื้นที่ DB
    }
}
