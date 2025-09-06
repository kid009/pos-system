<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'expense_category_id',
        'description',
        'amount',
        'expense_date',
        'created_by',
        'updated_by',
    ];

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
