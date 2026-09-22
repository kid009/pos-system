@php
    // ประกาศ Map รายการ Session Keys เข้ากับประเภทสีและไอคอนของ Alert
    $alerts = [
        'status'  => ['type' => 'success', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'icon_color' => 'text-emerald-500'],
        'success' => ['type' => 'success', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'icon_color' => 'text-emerald-500'],
        'error'   => ['type' => 'error',   'bg' => 'bg-rose-50',    'border' => 'border-rose-200',    'text' => 'text-rose-800',    'icon_color' => 'text-rose-500'],
        'warning' => ['type' => 'warning', 'bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-800',   'icon_color' => 'text-amber-500'],
        'info'    => ['type' => 'info',    'bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'text' => 'text-blue-800',    'icon_color' => 'text-blue-500'],
    ];
@endphp

<div class="space-y-3 w-full alert-container">
    @foreach ($alerts as $key => $style)
        @if (session()->has($key))
            @php
                $messages = session($key);
                // จัดการ Data Normalization: บังคับให้อยู่ในรูป Array เสมอ ป้องกัน Array-to-String Error
                $messages = is_array($messages) ? $messages : [$messages];
            @endphp

            <div class="system-alert relative flex items-start gap-3 p-4 rounded-xl border {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }} transition-all duration-300 shadow-sm"
                 role="alert">

                <!-- Semantic SVG Icon -->
                <div class="flex-shrink-0 mt-0.5 {{ $style['icon_color'] }}">
                    @if ($style['type'] === 'success')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif ($style['type'] === 'error')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif ($style['type'] === 'warning')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>

                <!-- Message Body -->
                <div class="flex-1 text-sm font-medium leading-relaxed">
                    @if (count($messages) === 1)
                        <p>{{ $messages[0] }}</p>
                    @else
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($messages as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Close / Dismiss Button (Vanilla JS Controlled) -->
                <button type="button"
                        class="alert-close-btn flex-shrink-0 -mr-1 -mt-1 p-1 rounded-lg hover:bg-black/5 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors"
                        aria-label="Close Alert">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    @endforeach
</div>

<!-- Scoped Vanilla JavaScript for Dismiss & Auto-Dismissal (Zero Alpine.js) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.system-alert');

        alerts.forEach(function (alert) {
            const closeBtn = alert.querySelector('.alert-close-btn');

            // ฟังก์ชันซ่อน Element ด้วย CSS Transition
            const dismissAlert = () => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-6px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            };

            // 1. กดปุ่มกากบาทเพื่อปิด
            if (closeBtn) {
                closeBtn.addEventListener('click', dismissAlert);
            }

            // 2. ตั้งเวลาปิดตัวเองอัตโนมัติใน 5 วินาที (เฉพาะ Success และ Status)
            // Error และ Warning ไม่ควรปิดอัตโนมัติ เพื่อให้ User อ่านสาเหตุทัน
            const isSuccessOrStatus = alert.classList.contains('bg-emerald-50') || alert.classList.contains('bg-blue-50');
            if (isSuccessOrStatus) {
                setTimeout(dismissAlert, 5000);
            }
        });
    });
</script>
