<?php

namespace App\Models;

use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Purchase extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id', 
        'branch_id', 
        'purchase_date', 
        'supplier_name', 
        'total_cost', 
        'created_by', 
        'updated_by'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
