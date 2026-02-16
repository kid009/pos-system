<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Helper Function 1: เช็คว่าเป็นเจ้าของร้านนี้หรือไม่?
     * @param int|Shop $shop
     * @return bool
     */
    public function isOwnerOf($shop)
    {
        // รองรับทั้งส่ง ID หรือส่ง Model Object มา
        $shopId = $shop instanceof Shop ? $shop->id : $shop;

        return $this->shops()
            ->where('shop_id', $shopId)
            ->wherePivot('role', 'shop_owner')
            ->exists();
    }

    /**
     * Helper Function 2: เช็คว่าเป็นพนักงานในร้านนี้หรือไม่? (รวมเจ้าของด้วย)
     * เอาไว้เช็คก่อนอนุญาตให้เข้าดู Dashboard ร้าน
     */
    public function belongsToShop($shop)
    {
        $shopId = $shop instanceof Shop ? $shop->id : $shop;

        return $this->shops()
                    ->where('shop_id', $shopId)
                    ->exists();
    }

    /**
     * Helper Function 3: ดึง Role ของ User ในร้านนั้นๆ
     */
    public function getRoleInShop($shopId)
    {
        $shop = $this->shops()->where('shop_id', $shopId)->first();
        return $shop ? $shop->pivot->role : null;
    }

    // 1. ความสัมพันธ์กับ Role
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    // 2. ฟังก์ชันเช็ค Role (Helper) เอาไว้ใช้ใน Blade หรือ Controller
    // ตัวอย่างการใช้: $user->hasRole('admin')
    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }
}
