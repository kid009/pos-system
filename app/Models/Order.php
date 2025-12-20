<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderItem;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, HasUserActivity;

    protected $fillable = [
        'user_id',
        'customer_id',
        'total_amount',
        'received_amount',
        'change_amount',
        'payment_type',
        'created_by',
        'updated_by'
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function cashier() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
