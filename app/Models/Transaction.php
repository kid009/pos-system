<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'customer_id',
        'total_amount',
        'receive_amount',
        'change_amount',
        'payment_method',
        'status',
        'invoice_no',
        'transaction_date',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
