<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasUserActivity;

    protected $fillable = [
        'shop_id',
        'name',
        'created_by',
        'updated_by'
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
}
