<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden - ไม่มีสิทธิ์เข้าถึง</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex items-center justify-center h-screen font-sans text-gray-900">

    <div class="max-w-md w-full px-6 py-8 bg-white shadow-lg rounded-lg border border-gray-200 text-center">

        {{-- Icon กุญแจ / แจ้งเตือน --}}
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-6">
            <svg class="h-8 w-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">403</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">ปฏิเสธการเข้าถึง (Access Denied)</h2>

        {{-- ดึงข้อความ Exception มาแสดง (ถ้ามีการ Custom ไว้) หรือแสดงข้อความ Default --}}
        <p class="text-gray-500 mb-8">
            {{ $exception->getMessage() ?: 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงหน้าจอหรือทำรายการนี้ กรุณาติดต่อ Super Admin หากคุณคิดว่านี่คือข้อผิดพลาด' }}
        </p>

        {{-- ปุ่มกลับไปหน้า Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center justify-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            กลับสู่หน้าหลัก
        </a>
    </div>

</body>

</html>
