<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity; // 🚨 1. นำเข้า Trait
use Spatie\Activitylog\LogOptions; // 🚨 2. นำเข้า Class สำหรับตั้งค่า

class Bank extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'account_name',
        'account_no',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
