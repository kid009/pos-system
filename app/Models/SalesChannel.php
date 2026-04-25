<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SalesChannel extends Model
{
    use HasFactory, LogsActivity;

    // ฟิลด์ที่อนุญาตให้แก้ไขได้
    protected $fillable = [
        'name',
        'is_active',
    ];

    // แปลงชนิดข้อมูล
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ตั้งค่าเก็บประวัติ (Audit Trail)
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Master Data: Sales Channel');
    }
}
