<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'latitude',
        'longitude',
        'line_id',
        'notes',
        'address',
        'type',
        'created_by',
        'updated_by',
    ];

    // ✅ Magic Function: บันทึก created_by/updated_by อัตโนมัติ
    protected static function booted()
    {
        static::creating(function ($customer) {
            if (Auth::check()) {
                $customer->created_by = Auth::id();
                $customer->updated_by = Auth::id();
            }
        });

        static::updating(function ($customer) {
            if (Auth::check()) {
                $customer->updated_by = Auth::id();
            }
        });
    }

    // ความสัมพันธ์กับ User (คนสร้าง)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ความสัมพันธ์กับ User (คนแก้ไขล่าสุด)
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
