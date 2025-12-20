<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public string $search = '';

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
        // dd($this->search);
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                // สร้างวงเล็บครอบเงื่อนไข OR ไว้ (Group) เพื่อไม่ให้ตีกับเงื่อนไขอื่น
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('code', 'like', '%' . $this->search . '%')
                             ->orWhere('phone', 'like', '%' . $this->search . '%')
                             ->orWhere('line_id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc') // เรียงจากใหม่ไปเก่า
            ->paginate(10);

        return view('livewire.customer.customer-index', [
            'customers' => $customers
        ]);
    }
}
