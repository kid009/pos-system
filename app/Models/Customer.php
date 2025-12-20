<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserActivity; // Import Trait

class Customer extends Model
{
    use HasFactory, HasUserActivity; // Use Trait

    protected $fillable = [
        'name',
        'phone',
        'points',
        'created_by',
        'updated_by'
    ];
}
