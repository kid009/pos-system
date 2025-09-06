<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'branch_id',
        'product_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'remaining_quantity',
        'created_by',
    ];
}
