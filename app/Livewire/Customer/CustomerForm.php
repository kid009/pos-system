<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;

class CustomerForm extends Component
{
    public $customerId;
    public $code, $name, $phone, $line_id, $latitude, $longitude, $notes, $address;
    public $type = 'general';

    public function mount($customer = null)
    {
        if ($customer) {
            $c = Customer::findOrFail($customer);
            $this->customerId = $c->id;
            $this->fill($c->only([
                'code', 'name', 'phone', 'line_id',
                'latitude', 'longitude', 'notes', 'address', 'type'
            ]));
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'phone' => $this->phone,
            'line_id' => $this->line_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'notes' => $this->notes,
            'address' => $this->address,
            'type' => $this->type,
        ];

        if ($this->customerId) {
            Customer::find($this->customerId)->update($data);
        } else {
            Customer::create($data);
        }

        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customer.customer-form');
    }
}
