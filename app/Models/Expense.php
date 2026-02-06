<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_date',
        'expense_category_id',
        'amount',
        'remark',
        'created_by',
        'updated_by'
    ];

    // 1 รายการค่าใช้จ่าย มี 1 ประเภทหมวดหมู่
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
