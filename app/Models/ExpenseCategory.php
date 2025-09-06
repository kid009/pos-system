<?php

namespace App\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
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
