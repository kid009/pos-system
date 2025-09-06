<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenseCategory::where('tenant_id', auth()->user()->tenant_id)
                                        ->latest()->paginate(10);

        return view('store.expense-categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('store.expense-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['created_by'] = auth()->id();

        ExpenseCategory::create($validated);

        return redirect()->route('store.expense-categories.index')->with('success', 'Expense category created.');
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
        $expenseCategory = ExpenseCategory::find($id);

        abort_if($expenseCategory->tenant_id != auth()->user()->tenant_id, 403);

        return view('store.expense-categories.edit', [
            'expenseCategory' => $expenseCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expenseCategory = ExpenseCategory::find($id);

        abort_if($expenseCategory->tenant_id != auth()->user()->tenant_id, 403);

        $validated = $request->validate(['name' => 'required|string|max:255']);

        $validated['updated_by'] = auth()->id();
        $expenseCategory->update($validated);

        return redirect()->route('store.expense-categories.index')->with('success', 'Expense category updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expenseCategory = ExpenseCategory::find($id);

        abort_if($expenseCategory->tenant_id != auth()->user()->tenant_id, 403);

        $expenseCategory->delete();

        return back()->with('success', 'Expense category deleted.');
    }
}
