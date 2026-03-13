<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Laravel Magic: ฟังก์ชันที่ชื่อ boot ตามด้วยชื่อ Trait
     * จะถูกเรียกใช้อัตโนมัติเมื่อ Model ถูก Boot
     */
    public static function bootAuditable(): void
    {
        static::creating(function ($model) {
            // เช็ค empty ป้องกันกรณีเราทำ Data Seeder หรือ System Command ที่บังคับใส่ ID มาแล้ว
            if (Auth::check()) {
                if (empty($model->created_by)) {
                    $model->created_by = Auth::id();
                }
                if (empty($model->updated_by)) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                // อัปเดตเสมอเมื่อมีการแก้ไขข้อมูลผ่าน Web/API
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * ดึงข้อมูลผู้สร้างรายการ
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * ดึงข้อมูลผู้แก้ไขล่าสุด
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
