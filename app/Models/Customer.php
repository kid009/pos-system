<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUserActivity; // Import Trait
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, HasUserActivity, SoftDeletes; // Use Trait

    protected $guarded = [];

    protected $fillable = [
        'name',
        'phone',
        'points',
        'created_by',
        'updated_by'
    ];

    public function transactions()
    {
        // ความสัมพันธ์แบบ One-to-Many (ลูกค้า 1 คน มีใบเสร็จได้หลายใบ)
        return $this->hasMany(Transaction::class);
    }
}
