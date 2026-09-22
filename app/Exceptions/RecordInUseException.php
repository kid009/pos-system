<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RecordInUseException extends Exception
{
    /**
     * คืนค่า Response อัตโนมัติ รองรับทั้งหน้าเว็บปกติและระบบ API/POS หน้าร้าน
     */
    public function render(Request $request): RedirectResponse|JsonResponse
    {
        // 1. ถ้าคำขอส่งมาจาก API, Mobile App หรือ Fetch/AJAX หน้าร้าน
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $this->getMessage(),
            ], Response::HTTP_CONFLICT); // HTTP 409 Conflict
        }

        // 2. ถ้าเป็นหน้าเว็บทั่วไป (Blade Form) ให้ Redirect กลับพร้อมข้อความเตือน
        return back()
            ->withInput()
            ->with('error', $this->getMessage());
    }
}
