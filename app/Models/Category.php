<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity; // 1. นำเข้า Trait
use Spatie\Activitylog\LogOptions; // 2. นำเข้า LogOptions คลาส

class Category extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    // สิ่งที่อนุญาตให้ Insert/Update ผ่าน Request ได้
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * บอก Laravel ให้ Generate UUID ลงในคอลัมน์ 'uuid' (ไม่ใช่ 'id')
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * ใช้ uuid เป็น key สำหรับ route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    /**
     * ตั้งค่าการบันทึก Activity Log ระดับ Enterprise
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // บอกให้ระบบเก็บประวัติการเปลี่ยนแปลงเฉพาะฟิลด์ที่อยู่ใน $fillable
            ->logFillable()
            // สั่งให้เก็บเฉพาะฟิลด์ที่มีการเปลี่ยนแปลงจริงๆ เท่านั้น (ป้องกันล็อกบวมจากค่าที่เหมือนเดิม)
            ->logOnlyDirty()
            // เปิดโหมดจับคู่ประวัติ บันทึกทั้งค่าเก่า (Before) และค่าใหม่ (After) ตอนกดอัปเดต
            ->dontSubmitEmptyLogs()
            // กำหนดชื่อ Log Description สื่อสารชัดเจนภาษาอังกฤษ
            ->useLogName('product_catalog');
    }
}
