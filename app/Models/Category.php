<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasUuids, HasFactory;

    // สิ่งที่อนุญาตให้ Insert/Update ผ่าน Request ได้
    protected $fillable = [
        'name',
        'is_active',
    ];


    /**
     * บอก Laravel ให้ Generate UUID ลงในคอลัมน์ 'uuid' (ไม่ใช่ 'id')
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
