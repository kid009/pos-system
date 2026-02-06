<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MainCategory extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่อนุญาตให้บันทึก
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];

    // (Option) ความสัมพันธ์กับ User เผื่ออยากดึงชื่อคนสร้างมาโชว์
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
