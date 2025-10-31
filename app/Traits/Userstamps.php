<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Userstamps
{
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootUserstamps()
    {
        // Event: "creating" - จะทำงานก่อนที่ Model จะถูกบันทึก (INSERT) ลงฐานข้อมูล
        static::creating(function ($model) {
            // ตรวจสอบว่ามีผู้ใช้งาน Login อยู่หรือไม่
            if (Auth::check()) {
                // ถ้ามี ให้ตั้งค่า created_by และ updated_by เป็น ID ของผู้ใช้ที่ Login อยู่
                $model->created_by = Auth::id();
            }
        });

        // Event: "updating" - จะทำงานก่อนที่ Model จะถูกอัปเดต (UPDATE)
        static::updating(function ($model) {
            // ตรวจสอบว่ามีผู้ใช้งาน Login อยู่หรือไม่
            if (Auth::check()) {
                // เมื่ออัปเดต ให้ตั้งค่า updated_by เป็น ID ของผู้ใช้ที่ Login อยู่เท่านั้น
                $model->updated_by = Auth::id();
            }
        });

        // (Optional) ถ้าคุณมี Soft Deletes และต้องการติดตามว่าใครลบ
        // static::deleting(function ($model) {
        //     if (Auth::check() && $model->isSoftDeleting()) {
        //         $model->deleted_by = Auth::id();
        //         $model->saveQuietly(); // บันทึกค่า deleted_by โดยไม่ trigger event อื่น
        //     }
        // });
    }
}