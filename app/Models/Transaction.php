<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
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
}
