<?php

namespace App\Models;

use Database\Factories\ProductCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['name', 'description', 'is_active'])]
class ProductCategory extends Model
{
    /** @use HasFactory<ProductCategoryFactory> */
    use HasFactory, LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to filter categories.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function (Builder $query) use ($filters) {
                $query->where('is_active', (bool) $filters['status']);
            });
    }

    /**
     * กำหนดค่าการตรวจสอบและบันทึกประวัติ
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 1. ระบุฟิลด์ที่อนุญาตให้บันทึกอย่างชัดเจน (ห้าม logAll เด็ดขาด)
            ->logOnly(['name', 'description', 'is_active'])
            // 2. บันทึกเฉพาะฟิลด์ที่มีการเปลี่ยนแปลงจริง ๆ เท่านั้น
            ->logOnlyDirty()
            // 3. ถ้าไม่มีฟิลด์ไหนเปลี่ยนเลย ไม่ต้องสร้างแถว Log ใน Database
            ->dontSubmitEmptyLogs()
            // 4. จัดหมวดหมู่ Log ให้อยู่ในกลุ่ม catalog เพื่อการสืบค้นที่รวดเร็ว
            ->useLogName('catalog')
            // 5. บันทึกข้อความสื่อความหมาย
            ->setDescriptionForEvent(fn (string $eventName) => "Product category has been {$eventName}");
    }
}
