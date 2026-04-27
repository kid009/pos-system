<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Purchase extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'doc_no',
        'supplier_id',
        'doc_date',
        'total_amount',
        'shipping_cost',
        'discount',
        'net_amount',
        'status',
    ];

    protected $casts = [
        'doc_date' => 'date',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    // 🔗 1 หัวบิล เป็นของ 1 ซัพพลายเออร์
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // 🔗 1 หัวบิล มีได้หลายรายการสินค้า (รอสร้างตาราง purchase_details)
    // public function details()
    // {
    //     return $this->hasMany(PurchaseDetail::class);
    // }

    // ตั้งค่าเก็บประวัติ (Audit Trail)
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Inventory: Purchase Inbound');
    }
}
