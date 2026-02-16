<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    // ถ้าชื่อตารางเป็น 'shops' Laravel จะรู้เอง แต่ใส่ไว้เพื่อความชัวร์ก็ได้
    protected $table = 'shops';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'logo_path',
        'created_by',
        'updated_by',
    ];

    // ความสัมพันธ์: ร้านนี้มีผู้ใช้คนไหนบ้าง (Many-to-Many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'shop_user')
                    ->withPivot('role') // ดึงข้อมูล role ในตารางกลางมาด้วย
                    ->withTimestamps();
    }

    // ความสัมพันธ์: ร้านนี้มี Category อะไรบ้าง (One-to-Many)
    // เปลี่ยนจาก mainCategory เดิม
    public function categories()
    {
        return $this->hasMany(Category::class, 'shop_id');
    }

    /**
     * ความสัมพันธ์: ร้านนี้มีรายการขายอะไรบ้าง (One-to-Many)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
