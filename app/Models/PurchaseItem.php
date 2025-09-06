<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'purchase_id', 
        'product_id', 
        'quantity', 
        'cost'
    ];
    
    public $timestamps = false; // ตารางนี้อาจไม่ต้องการ timestamps

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
