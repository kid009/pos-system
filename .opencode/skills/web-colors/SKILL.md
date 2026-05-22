---
name: web-colors
description: ใช้เมื่อต้องการกำหนดหรืออ้างอิงสีของเว็บไซต์ POS System รวมถึง color palette, Tailwind CSS classes, และการจัดการธีมสี
---

# Web Colors Skill - POS System Color Palette

## Color Palette หลักของระบบ POS

### สีหลัก (Primary Colors)
| ชื่อ | Hex | Tailwind Class | ใช้สำหรับ |
|------|-----|----------------|----------|
| Primary Blue | `#3B82F6` | `blue-500` | ปุ่มหลัก, ลิงก์, ไอคอนสำคัญ |
| Primary Dark | `#1D4ED8` | `blue-700` | ปุ่ม hover, สถานะ active |
| Primary Light | `#93C5FD` | `blue-300` | พื้นหลังไฮไลท์ |

### สีรอง (Secondary Colors)
| ชื่อ | Hex | Tailwind Class | ใช้สำหรับ |
|------|-----|----------------|----------|
| Emerald | `#10B981` | `emerald-500` | สถานะสำเร็จ, ยอดขายดี |
| Emerald Dark | `#047857` | `emerald-700` | ปุ่ม success hover |
| Amber | `#F59E0B` | `amber-500` | การแจ้งเตือน, สถานะรอดำเนินการ |
| Rose | `#F43F5E` | `rose-500` | ข้อผิดพลาด, การลบ, สต็อกต่ำ |

### สีพื้นหลัง (Background Colors)
| ชื่อ | Hex | Tailwind Class | ใช้สำหรับ |
|------|-----|----------------|----------|
| White | `#FFFFFF` | `white` | พื้นหลังหลัก |
| Gray 50 | `#F9FAFB` | `gray-50` | พื้นหลังรอง, การ์ด |
| Gray 100 | `#F3F4F6` | `gray-100` | พื้นหลัง sidebar |
| Gray 200 | `#E5E7EB` | `gray-200` | เส้นขอบ, แบ่งส่วน |

### สีข้อความ (Text Colors)
| ชื่อ | Hex | Tailwind Class | ใช้สำหรับ |
|------|-----|----------------|----------|
| Gray 900 | `#111827` | `gray-900` | ข้อความหลัก |
| Gray 700 | `#374151` | `gray-700` | ข้อความรอง |
| Gray 500 | `#6B7280` | `gray-500` | ข้อความ placeholder |
| Gray 400 | `#9CA3AF` | `gray-400` | ข้อความ disabled |

## การใช้งานใน Blade Templates

```blade
{{-- ปุ่มหลัก --}}
<button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
    บันทึก
</button>

{{-- ปุ่ม Success --}}
<button class="bg-emerald-500 hover:bg-emerald-700 text-white px-4 py-2 rounded">
    สำเร็จ
</button>

{{-- ปุ่ม Danger --}}
<button class="bg-rose-500 hover:bg-rose-700 text-white px-4 py-2 rounded">
    ลบ
</button>

{{-- การ์ด --}}
<div class="bg-white rounded-lg shadow border border-gray-200 p-4">
    <h2 class="text-gray-900 font-semibold">หัวข้อ</h2>
    <p class="text-gray-700">เนื้อหา</p>
</div>

{{-- Badge สถานะ --}}
<span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm">
    เปิดใช้งาน
</span>

<span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
    รอดำเนินการ
</span>

<span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-sm">
    ยกเลิก
</span>
```

## การใช้งานกับสถานะสินค้า

| สถานะ | สี | Tailwind Classes |
|-------|-----|-----------------|
| มีสต็อก | เขียว | `bg-emerald-100 text-emerald-700` |
| สต็อกต่ำ | ส้ม | `bg-amber-100 text-amber-700` |
| หมดสต็อก | แดง | `bg-rose-100 text-rose-700` |
| ระงับ | เทา | `bg-gray-100 text-gray-700` |

## การใช้งานกับยอดขาย

```blade
{{-- แสดงยอดขายบวก --}}
<span class="text-emerald-500 font-semibold">+12.5%</span>

{{-- แสดงยอดขายลบ --}}
<span class="text-rose-500 font-semibold">-5.2%</span>

{{-- กราฟแท่ง --}}
<div class="bg-blue-500 h-4 rounded" style="width: 75%"></div>
```

## Dark Mode (ถ้ามี)

```blade
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    {{-- Content --}}
</div>
```

## หลักการใช้สี

1. **Consistency**: ใช้สีชุดเดียวกันทั้งระบบ
2. **Accessibility**: ตรวจสอบ contrast ratio ระหว่างข้อความและพื้นหลัง
3. **Semantics**: ใช้สีที่สื่อความหมายตรงกับการกระทำ (สีเขียว=สำเร็จ, สีแดง=อันตราย)
4. **Hierarchy**: ใช้สีเทาสำหรับข้อมูลรอง สีเข้มสำหรับข้อมูลหลัก
