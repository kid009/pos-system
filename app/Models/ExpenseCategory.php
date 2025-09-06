<?php

namespace App\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id', 
        'name', 
        'created_by', 
        'updated_by'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
