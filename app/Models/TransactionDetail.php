<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = []; // อนุญาตให้ create ข้อมูลได้ทุกช่อง

    public function transaction()
    {
        // belongsTo แปลว่า "เป็นของ" (รายการสินค้านี้ เป็นของ Transaction ไหน)
        return $this->belongsTo(Transaction::class);
    }
}
