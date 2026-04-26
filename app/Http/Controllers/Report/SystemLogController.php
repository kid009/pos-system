<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. รับค่าจากฟอร์มแยกแต่ละช่อง
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $causer = $request->input('causer');
        $logName = $request->input('log_name');
        $description = $request->input('description');

        // 2. Query ดึงข้อมูลตามเงื่อนไข
        $logs = Activity::with(['causer', 'subject'])
            // กรองวันที่เริ่มต้น
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            // กรองวันที่สุดท้าย
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            // กรองโมดูล (ตาราง)
            ->when($logName, function ($q) use ($logName) {
                $q->where('log_name', $logName);
            })
            // กรองเหตุการณ์ (สร้าง, แก้ไข, ลบ)
            ->when($description, function ($q) use ($description) {
                $q->where('description', $description);
            })
            // กรองชื่อผู้ทำรายการ (ค้นหาผ่าน Relationship ไปยังตาราง Users)
            ->when($causer, function ($q) use ($causer) {
                $q->whereHasMorph('causer', [User::class], function ($userQuery) use ($causer) {
                    $userQuery->where('name', 'like', "%{$causer}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // 3. ดึงรายชื่อโมดูลและเหตุการณ์ทั้งหมดที่มีในระบบ เพื่อเอาไปสร้างเป็น Dropdown ให้ผู้ใช้เลือกง่ายๆ
        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');
        $events = Activity::select('description')->distinct()->pluck('description');

        return view('reports.system-logs.index', compact(
            'logs',
            'startDate',
            'endDate',
            'causer',
            'logName',
            'description',
            'logNames',
            'events'
        ));
    }
}
