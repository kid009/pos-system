<?php

namespace App\Livewire\Admin;

use App\Models\ExpenseCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ExpenseCategoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name;
    public $group;
    public $search = '';

    public function openCreateModal()
    {
        $this->reset(['name', 'group']);

        $this->dispatch('show-expense-category-modal');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:255',
        ]);

        ExpenseCategory::create([
            'name' => $this->name,
            'group' => $this->group,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $this->dispatch('close-expense-category-modal');
        $this->dispatch('notify', message: 'Expense category created successfully.', type: 'success');
    }

    public function render()
    {
        $expenseCategories = ExpenseCategory::orderBy('id', 'desc')
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.expense-category-component', [
            'expenseCategories' => $expenseCategories,
        ]);
    }
}
