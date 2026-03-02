<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory, HasUserActivity, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'name',
        'created_by',
        'updated_by',
        'is_tracking_stock',
    ];

    // บังคับให้ Laravel แปลง 0/1 ใน DB เป็น false/true ให้อัตโนมัติ
    protected $casts = [
        'is_tracking_stock' => 'boolean',
    ];

    public function shop() // เปลี่ยนชื่อฟังก์ชันจาก mainCategory เป็น shop
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope สำหรับกรองข้อมูลตามสิทธิ์ของ User
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeForUser(Builder $query, $user)
    {
        // 1. ดึง ID ร้านค้าปัจจุบันจาก Session (ที่ Middleware CheckShopSelected สร้างไว้)
        $currentUser = Auth::user();
        $currentShopId = $currentUser->shops()->first()->id ?? 0;

        // 2. ถ้ามี Session ร้านค้า (ซึ่งควรจะมีเสมอเมื่อผ่าน Middleware)
        // บังคับให้แสดงหมวดหมู่ "เฉพาะของร้านนี้" เท่านั้น!
        // (รองรับทั้ง Admin ที่เพิ่งกดเลือกร้านมา และ Owner/Staff ที่ผูกติดกับร้านนี้)
        if ($currentShopId) {
            return $query->where('shop_id', $currentShopId);
        }

        // 3. (Fallback) กรณีฉุกเฉิน Session หลุด หรือเรียกผ่าน API/Command
        if ($user->role === 'admin') {
            // แอดมินทะลุทะลวง เห็นทุกหมวดหมู่ของทุกร้าน
            return $query;
        }

        // พนักงาน/เจ้าของร้าน เห็นเฉพาะร้านตัวเอง
        return $query->whereHas('shop.users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }

    public function isTrackingStock(): bool
    {
        return (bool) $this->is_tracking_stock;
    }
}
