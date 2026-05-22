<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockMovement extends Model
{
    use HasUuids, LogsActivity;

    // Mass Assignable fields (Protected from financial manipulation)
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'user_id',
        'type',
        'qty',
        'unit_cost',
        'reference',
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_cost' => 'float',
        'created_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Failsafe Architectural Layer: Intercept updating/deleting to enforce immutability.
     */
    #[Override]
    public static function booted()
    {
        static::updating(function ($movement) {
            throw new \Exception("Architectural Violation: Stock movements are an unalterable ledger and cannot be updated.");
        });

        static::deleting(function ($movement) {
            throw new \Exception("Architectural Violation: Stock movements are an unalterable ledger and cannot be deleted.");
        });
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
