<?php

namespace App\Livewire\Store\Purchases;

use Log;
use App\Models\Product;
use Livewire\Component;
use App\Services\Inventory\StockInService;

class CreatePurchase extends Component
{
    public $purchase_date;
    public $supplier_name;
    public $items = [];
    public $allProducts = [];

    protected $rules = [
        'purchase_date' => 'required|date',
        'supplier_name' => 'nullable|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.cost' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->allProducts = Product::where('tenant_id', auth()->user()->tenant_id)->orderBy('name')->get();
        $this->purchase_date = date('Y-m-d');
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'cost' => 0]; // Add one empty item on load
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'cost' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Re-index the array
    }

    public function save(StockInService $stockInService)
    {
        $validatedData = $this->validate();

        try 
        {
            $stockInService->handle($validatedData, auth()->user());
            session()->flash('success', 'Purchase recorded and stock updated successfully.');
            return redirect()->route('store.purchases.index');
        } 
        catch (\Exception $e) 
        {
            Log::error('Stock-in failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to record purchase. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.store.purchases.create-purchase');
    }
}
