<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    protected $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Customer::find($id)->delete();
        session()->flash('success', 'ลบข้อมูลเรียบร้อย');
    }

    public function render()
    {
        $customers = Customer::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('code', 'like', "%{$this->search}%")
            ->orWhere('phone', 'like', "%{$this->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.customer.customer-index', [
            'customers' => $customers,
        ]);
    }
}
