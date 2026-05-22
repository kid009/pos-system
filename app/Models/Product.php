<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes, LogsActivity;

    // สิ่งที่อนุญาตให้ Insert/Update ผ่าน Request ได้
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'price',
        'is_active',
    ];

    // สิ่งที่ห้ามส่งออกไปทาง API
    protected $hidden = [
        'id', // ซ่อน ID จริง
        'average_cost',
    ];

    protected $casts = [
        'price' => 'float',
        'average_cost' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
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

    // Relationship
    // การใช้ withDefault() ช่วยป้องกัน "Trying to get property of non-object" หากสินค้านั้นไม่มีหมวดหมู่ ระบบจะคืนค่า Object จำลองกลับมาแทน NULL
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')
            ->withDefault([
                'name' => 'Uncategorized',
                'uuid' => null,
            ]);
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
            ->useLogName('product');
    }
}
