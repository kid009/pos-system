<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * ฟังก์ชันครอบ Try-Catch แบบสำเร็จรูป ป้องกันข้อมูลพังและเก็บ Log
     */
    protected function executeSafely(\Closure $action, $successMessage = 'ทำรายการสำเร็จ')
    {
        DB::beginTransaction();

        try {
            // รันโค้ดที่ส่งเข้ามา
            $result = $action();

            DB::commit(); // ยืนยันการบันทึก

            // ส่งกลับหน้าเดิมพร้อมข้อความสำเร็จ
            return redirect()->back()->with('success', $successMessage);
        } catch (Exception $e) {
            DB::rollBack(); // ถอยกลับข้อมูลทั้งหมด

            // บันทึก Error ไว้ดูหลังบ้าน
            Log::error('CRUD Error: ' . $e->getMessage());

            // ส่งกลับหน้าเดิมพร้อมข้อความ Error
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
