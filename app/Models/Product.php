<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    // สิ่งที่อนุญาตให้ Insert/Update ผ่าน Request ได้
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'price',
        'is_active',
    ];

    // สิ่งที่ห้ามส่งออกไปทาง API
    protected $hidden = [
        'id', // ซ่อน ID จริง
        'average_cost',
    ];

    protected $casts = [
        'price' => 'float',
        'average_cost' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * บอก Laravel ให้ Generate UUID ลงในคอลัมน์ 'uuid' (ไม่ใช่ 'id')
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    // Relationship
    //การใช้ withDefault() ช่วยป้องกัน "Trying to get property of non-object" หากสินค้านั้นไม่มีหมวดหมู่ ระบบจะคืนค่า Object จำลองกลับมาแทน NULL
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')
            ->withDefault([
                'name' => 'Uncategorized',
                'uuid' => null
            ]);
    }
}
