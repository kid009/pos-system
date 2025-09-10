<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'tenant_id',
        'product_category_id',
        'name',
        'sku',
        'description',
        'image',
        'cost',
        'price',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    /**
     * Get the child menu items.
     * ความสัมพันธ์: เมนูหนึ่งรายการ สามารถมีเมนูย่อย (children) ได้หลายรายการ
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        // เป็นการสร้าง one-to-many relationship กับ Model ตัวเอง
        // โดยดูจากคอลัมน์ 'parent_id'
        return $this->hasMany(Menu::class, 'parent_id');
    }

    /**
     * Get the parent menu item.
     * ความสัมพันธ์: เมนูหนึ่งรายการ (ที่เป็นเมนูย่อย) เป็นของเมนูหลัก (parent) ได้หนึ่งรายการ
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
    
}
