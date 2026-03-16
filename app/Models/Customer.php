<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    use Auditable; // 💡 สถาปัตยกรรมใหม่: ดึง Audit Trail มาใช้บรรทัดเดียวจบ!

    protected $fillable = [
        'shop_id',
        'name',
        'phone',
        'address',
        'tax_id',
        'points',
        'is_active'
    ];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    // เผื่ออนาคต: ดึงประวัติการซื้อของลูกค้ารายนี้
    // public function transactions() {
    //     return $this->hasMany(Transaction::class);
    // }
}
