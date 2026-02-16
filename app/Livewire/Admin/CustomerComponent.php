<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Services\LogService; // ✅ เรียกใช้ LogService
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
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

    // Validation Rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:customers,phone,' . $this->customerId,
            'points' => 'integer|min:0'
        ];
    }

    public function create()
    {
        $this->reset(['customerId', 'name', 'phone', 'points']);
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        try {
            // ✅ สร้างข้อมูล
            $customer = Customer::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'points' => $this->points ?? 0,
            ]);

            // 📝 LOG INFO: สร้างลูกค้าใหม่
            LogService::info('Customer Created', [
                'customer_id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone
            ]);

            $this->isOpen = false;
            $this->dispatch('notify', message: 'Customer added successfully!', type: 'success');
        } catch (Exception $e) {
            // 📝 LOG ERROR: สร้างไม่สำเร็จ
            LogService::error('Customer Create Failed', $e, [
                'name_attempt' => $this->name,
                'phone_attempt' => $this->phone
            ]);

            $this->dispatch('notify', message: 'Error adding customer: ' . $e->getMessage(), type: 'error');
        }
    }

    public function edit($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $this->customerId = $id;
            $this->name = $customer->name;
            $this->phone = $customer->phone;
            $this->points = $customer->points;

            $this->isOpen = true;
        } catch (Exception $e) {
            LogService::warning('Customer Edit Not Found', ['id' => $id]);
            $this->dispatch('notify', message: 'Customer not found.', type: 'error');
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $customer = Customer::findOrFail($this->customerId);

            // เก็บข้อมูลเก่าไว้ Log เปรียบเทียบ (Optional)
            $oldData = ['name' => $customer->name, 'points' => $customer->points];

            $customer->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'points' => $this->points,
            ]);

            // 📝 LOG INFO: อัปเดตข้อมูลลูกค้า
            LogService::info('Customer Updated', [
                'customer_id' => $customer->id,
                'changes' => [
                    'from' => $oldData,
                    'to' => ['name' => $this->name, 'points' => $this->points]
                ]
            ]);

            $this->isOpen = false;
            $this->dispatch('notify', message: 'Customer updated successfully!', type: 'success');
        } catch (Exception $e) {
            LogService::error('Customer Update Failed', $e, ['customer_id' => $this->customerId]);
            $this->dispatch('notify', message: 'Error updating customer.', type: 'error');
        }
    }

    public function delete($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                $this->dispatch('notify', message: 'Customer not found.', type: 'error');
                return;
            }

            // ✅ INTEGRITY CHECK: ถ้าลูกค้ามีประวัติการซื้อ (Transactions) ห้ามลบ!
            // เพราะจะทำให้ประวัติการขายเก่าๆ เสียหาย หรือยอด Member หายไป
            if ($customer->transactions()->exists()) {

                LogService::warning('Customer Delete Blocked (Has History)', [
                    'customer_id' => $id,
                    'name' => $customer->name
                ]);

                $this->dispatch('notify', message: 'Cannot delete customer with purchase history.', type: 'error');
                return;
            }

            // ถ้าไม่มีประวัติ ลบได้
            $deletedName = $customer->name;
            $customer->delete();

            // 📝 LOG INFO
            LogService::info('Customer Deleted', [
                'customer_id' => $id,
                'name' => $deletedName
            ]);

            $this->dispatch('notify', message: 'Customer deleted successfully.', type: 'success');
        } catch (Exception $e) {
            LogService::error('Customer Delete Failed', $e, ['customer_id' => $id]);
            $this->dispatch('notify', message: 'Error deleting customer.', type: 'error');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

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
}
