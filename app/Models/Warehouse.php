<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Warehouse extends Model
{
    use HasUlids, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $hidden = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
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
