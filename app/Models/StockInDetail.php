<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class StockInDetail extends Model
{
    protected $fillable = [
        'stock_in_id',
        'product_id',
        'qty',
        'unit_cost',
        'total_cost'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
