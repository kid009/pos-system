<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory, Userstamps;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    // (Optional but recommended) สร้าง Relationship ไปยัง User
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
