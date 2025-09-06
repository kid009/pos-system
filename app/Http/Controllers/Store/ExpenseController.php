<?php

namespace App\Http\Controllers\Store;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::with('expenseCategory') // Eager load category
            ->where('branch_id', auth()->user()->branch_id)
            ->latest('expense_date') // เรียงตามวันที่เกิดรายจ่ายล่าสุด
            ->paginate(15);

        return view('store.expenses.index', [
            'expenses' => $expenses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ExpenseCategory::where('tenant_id', auth()->user()->tenant_id)->get();
        
        return view('store.expenses.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['branch_id'] = auth()->user()->branch_id;
        $validated['created_by'] = auth()->id();
        Expense::create($validated);

        return redirect()->route('store.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::find($id);

        abort_if($expense->branch_id != auth()->user()->branch_id, 403);

        $categories = ExpenseCategory::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('store.expenses.edit', [
            'expense' => $expense,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::find($id);

        abort_if($expense->branch_id != auth()->user()->branch_id, 403);
        // ... Validation and update logic similar to store() ...
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);
        $validated['updated_by'] = auth()->id();
        $expense->update($validated);

        return redirect()->route('store.expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);

        abort_if($expense->branch_id != auth()->user()->branch_id, 403);
        $expense->delete();
        return back()->with('success', 'Expense deleted successfully.');
    }
}
