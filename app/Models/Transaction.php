<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'reference_no',
        'user_id',
        'customer_id',
        'total_amount',
        'delivery_fee',
        'discount_amount',
        'received_amount',
        'change_amount',
        'payment_method',
        'status',
        'note',
        'transaction_date',
        'shop_id',
        'created_by',
        'updated_by',
    ];

    protected $guarded = []; // อนุญาตให้ create ข้อมูลได้ทุกช่อง

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

     /**
     * ความสัมพันธ์: บิลนี้เป็นของร้านไหน (BelongsTo)
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
