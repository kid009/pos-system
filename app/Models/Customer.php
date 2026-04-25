<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity; // 🚨 1. นำเข้า Trait
use Spatie\Activitylog\LogOptions; // 🚨 2. นำเข้า Class สำหรับตั้งค่า

class Customer extends Model
{
    use HasFactory;
    use Auditable; // 💡 สถาปัตยกรรมใหม่: ดึง Audit Trail มาใช้บรรทัดเดียวจบ!

    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'address',
        'tax_id',
        'points',
        'is_active'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // 🚨 4. ตั้งค่าเงื่อนไขการเก็บ Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // สั่งให้จับตาดูทุกฟิลด์ที่อยู่ใน $fillable
            ->logOnlyDirty() // เก็บเฉพาะฟิลด์ที่มีการเปลี่ยนแปลงค่าจริงๆ (ลดขนาด Database)
            ->dontSubmitEmptyLogs() // ถ้ากด Save แต่ไม่มีอะไรเปลี่ยน ไม่ต้องบันทึก Log
            ->useLogName('Master Data: Bank'); // ตั้งชื่อกลุ่มของ Log ให้อ่านง่าย
    }
}
