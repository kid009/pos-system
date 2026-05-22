<?php

namespace Tests\Feature\Theme;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class AdminComponentTest extends TestCase
{
    public function test_admin_button_component_renders_correct_classes_based_on_variant()
    {
        // Act: ทดสอบ Render ปุ่ม variant="danger"
        $rendered = Blade::render('<x-admin.button variant="danger">Delete</x-admin.button>');

        // Assert: ต้องมีคลาสของสี Rose (สีแดง) ปรากฏอยู่
        $this->assertStringContainsString('bg-rose-500', $rendered);
        $this->assertStringContainsString('text-white', $rendered);
        $this->assertStringContainsString('Delete', $rendered);
    }

    public function test_admin_badge_component_renders_success_status()
    {
        // Act: ทดสอบ Render ป้ายสถานะ success
        $rendered = Blade::render('<x-admin.badge status="success">In Stock</x-admin.badge>');

        // Assert: ต้องมีคลาสของสี Emerald (สีเขียว)
        $this->assertStringContainsString('bg-emerald-100', $rendered);
        $this->assertStringContainsString('text-emerald-700', $rendered);
        $this->assertStringContainsString('In Stock', $rendered);
    }
}
