<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Manage Customers')]
class CustomerComponent extends Component
{
    use WithPagination;

    // Config Theme ให้ Pagination สวยงามตามที่เราเพิ่งแก้
    protected $paginationTheme = 'bootstrap';

    public $customerId;
    public $name, $phone, $points = 0; // Default points = 0
    public $search = '';
    public $isOpen = false;

    public function render()
    {
        $customers = Customer::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.customer-component', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        $this->reset(['customerId', 'name', 'phone', 'points']);
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone', // เบอร์ห้ามซ้ำ
            'points' => 'integer|min:0'
        ]);

        Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'points' => $this->points ?? 0,
        ]);

        $this->isOpen = false;

        // ✅ เรียกใช้ Global Alert
        $this->dispatch('notify', message: 'Customer added successfully!', type: 'success');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->customerId = $id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->points = $customer->points;

        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            // Senior Tip: ignore($id) เพื่อให้แก้ข้อมูลอื่นได้โดยไม่ต้องเปลี่ยนเบอร์
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $this->customerId,
            'points' => 'integer|min:0'
        ]);

        $customer = Customer::findOrFail($this->customerId);
        $customer->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'points' => $this->points,
        ]);

        $this->isOpen = false;

        // ✅ เรียกใช้ Global Alert
        $this->dispatch('notify', message: 'Customer updated successfully!', type: 'success');
    }

    public function delete($id)
    {
        // อนาคตอาจต้องเช็คก่อนลบ ว่าลูกค้าคนนี้มี Order ค้างอยู่ไหม
        Customer::find($id)->delete();

        // ✅ เรียกใช้ Global Alert
        $this->dispatch('notify', message: 'Customer deleted successfully.', type: 'success');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }
}
