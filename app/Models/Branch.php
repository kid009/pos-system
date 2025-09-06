<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'phone',
        'is_main',
    ];

    //1 Branch เป็นของ 1 Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
