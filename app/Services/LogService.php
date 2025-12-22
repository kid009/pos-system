<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService
{
    /**
     * บันทึก Log ทั่วไป (Info)
     * ใช้สำหรับ: Login สำเร็จ, สร้างบิล, แก้ไขข้อมูล
     */
    public static function info(string $action, array $context = [])
    {
        self::record('info', $action, $context);
    }

    /**
     * บันทึก Log แจ้งเตือน (Warning)
     * ใช้สำหรับ: Login ผิด, สิทธิ์ไม่พอ, ตัดสต็อกไม่ผ่าน
     */
    public static function warning(string $action, array $context = [])
    {
        self::record('warning', $action, $context);
    }

    /**
     * บันทึก Log ข้อผิดพลาด (Error)
     * ใช้สำหรับ: System Crash, Database Error, Exception
     */
    public static function error(string $action, \Throwable $e = null, array $context = [])
    {
        // เพิ่มข้อมูล Error เข้าไปใน Context
        if ($e) {
            $context['error_message'] = $e->getMessage();
            $context['file'] = $e->getFile();
            $context['line'] = $e->getLine();
            $context['trace'] = $e->getTraceAsString(); // เก็บ Trace ไว้ไล่ดูจุดพัง
        }

        self::record('error', $action, $context);
    }

    /**
     * Critical Alert (เรื่องคอขาดบาดตาย)
     * ใช้สำหรับ: Stock หาย, ยอดเงินไม่ตรง, พบการ Hack
     */
    public static function critical(string $action, array $context = [])
    {
        self::record('critical', $action, $context);
    }

    /**
     * ฟังก์ชันกลางสำหรับรวบรวมข้อมูลมาตรฐาน (Centralized Builder)
     */
    private static function record(string $level, string $action, array $context)
    {
        // 1. เตรียมข้อมูลมาตรฐาน (Who, Where)
        $standardInfo = [
            'user_id'    => Auth::id() ?? 'GUEST', // ถ้ายังไม่ login ให้เป็น GUEST
            'user_email' => Auth::user()->email ?? null,
            'ip'         => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'url'        => Request::fullUrl(),
            'method'     => Request::method(),
        ];

        // 2. รวมข้อมูลมาตรฐาน เข้ากับข้อมูลเฉพาะ (Context)
        $finalData = array_merge($standardInfo, $context);

        // 3. ส่งให้ Laravel Log (File/Database/Slack)
        // Format: [ACTION NAME] { JSON DATA }
        Log::$level($action, $finalData);
    }
}
