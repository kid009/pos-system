<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ตารางร้านค้า (Shops)
        // เช็คก่อนว่ามีตารางนี้หรือยัง (กัน Error)
        if (!Schema::hasTable('shops')) {
            // ถ้ามีตารางเก่าชื่อ main_categories ให้เปลี่ยนชื่อ
            if (Schema::hasTable('main_categories')) {
                Schema::rename('main_categories', 'shops');
            } else {
                // ถ้าไม่มีเลย ให้สร้างใหม่
                Schema::create('shops', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('address')->nullable();
                    $table->string('phone')->nullable();
                    $table->string('logo_path')->nullable();
                    $table->timestamps();
                });
            }
        }

        // เพิ่มคอลัมน์ให้ shops (เผื่อตารางเดิมไม่มี)
        if (Schema::hasTable('shops')) {
            Schema::table('shops', function (Blueprint $table) {
                if (!Schema::hasColumn('shops', 'address')) $table->string('address')->nullable();
                if (!Schema::hasColumn('shops', 'phone')) $table->string('phone')->nullable();
                if (!Schema::hasColumn('shops', 'logo_path')) $table->string('logo_path')->nullable();
            });
        }

        // 2. ตารางเชื่อม Users <-> Shops
        if (!Schema::hasTable('shop_user')) {
            Schema::create('shop_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
                $table->string('role')->default('staff')->comment('owner, manager, staff');
                $table->timestamps();

                $table->unique(['user_id', 'shop_id']); // ห้ามจับคู่ซ้ำ
            });
        }

        // 3. ปรับตาราง Categories ให้มี shop_id
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                // ถ้ามี main_category_id ให้เปลี่ยนชื่อ
                if (Schema::hasColumn('categories', 'main_category_id')) {
                    $table->renameColumn('main_category_id', 'shop_id');
                }
                // ถ้ายังไม่มี shop_id ให้สร้างใหม่
                elseif (!Schema::hasColumn('categories', 'shop_id')) {
                    $table->foreignId('shop_id')->nullable()->constrained('shops')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        // ย้อนกลับ
        Schema::dropIfExists('shop_user');

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'shop_id')) {
                    $table->renameColumn('shop_id', 'main_category_id');
                }
            });
        }

        Schema::rename('shops', 'main_categories');
    }
};
