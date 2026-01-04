<?php

namespace App\Models;

use App\Models\User;
use App\Models\StockInDetail;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $fillable = [
        'import_date',
        'supplier_name',
        'payment_type',
        'total_amount',
        'user_id'
    ];

    public function details()
    {
        return $this->hasMany(StockInDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // สร้าง Attribute ชื่อ payment_type_label (เรียกใช้โดย $stockIn->payment_type_label)
    public function getPaymentTypeLabelAttribute()
    {
        return match ($this->payment_type){
            'cash' => 'เงินสด',
            'credit' => 'ค้างชำระ/เครดิต',
            'transfer' => 'โอนธนาคาร',
            default => $this->payment_type,
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match ($this->payment_type){
            'cash' => 'success',
            'credit' => 'warning',
            'transfer' => 'info',
            default => 'secondary',
        };
    }

}
