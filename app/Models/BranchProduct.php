<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchProduct extends Model
{
    use HasFactory;
    
    protected $table = 'branch_product';
    
    protected $fillable = [
        'branch_id',
        'product_id',
        'quantity',
    ];
}
