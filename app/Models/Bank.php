<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'account_name',
        'account_no',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
