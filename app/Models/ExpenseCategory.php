<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group',
        'color',
        'is_active',
        'created_by',
        'updated_by'
    ];

    //1 ประเภทหมวดหมู่ มีหลายรายการค่าใช้จ่าย
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
