<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="expense_date" class="form-label">Expense Date</label>
            <input type="date" class="form-control" name="expense_date" id="expense_date" value="{{ old('expense_date', isset($expense) ? \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') : date('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-6">
         <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" class="form-control" name="amount" id="amount" value="{{ old('amount', $expense->amount ?? '') }}" required>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="expense_category_id" class="form-label">Expense Category</label>
    <select name="expense_category_id" id="expense_category_id" class="form-select" required>
        <option value="">-- Select Category --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('expense_category_id', $expense->expense_category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $expense->description ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">{{ isset($expense) ? 'Update' : 'Create' }}</button>
<a href="{{ route('store.expenses.index') }}" class="btn btn-secondary">Cancel</a>