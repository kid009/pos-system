<?php

namespace Tests\Feature\Theme;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LayoutResolutionTest extends TestCase
{
    public function test_admin_layout_component_can_be_resolved_and_rendered()
    {
        // Arrange: จำลองการเขียนโค้ดเรียกใช้งาน Component
        $bladeSnippet = '
            <x-admin-layout>
                <div class="test-content">สวัสดีระบบ POS</div>
            </x-admin-layout>
        ';

        // Act: สั่งเรนเดอร์สตริงผ่าน Blade Engine
        $renderedHtml = Blade::render($bladeSnippet);

        // Assert: ตรวจสอบว่าต้องมีเนื้อหาภายในเรนเดอร์ออกมาได้สำเร็จ ไม่เกิด Error
        $this->assertStringContainsString('สวัสดีระบบ POS', $renderedHtml);
        $this->assertStringContainsString('POS System', $renderedHtml); // ต้องเจอคำนี้ที่มีอยู่ในโครงสร้างหลักของ layouts.admin
    }
}
