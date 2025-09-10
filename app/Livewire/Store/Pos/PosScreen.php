<?php

namespace App\Livewire\Store\Pos;

use App\Models\Product;
use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use App\Services\Order\CreateOrderService;

class PosScreen extends Component
{
    use WithPagination;

    public $cart = [];
    public $allCustomers = [];
    public $selectedCustomerId;
    public $search = '';

    public function mount()
    {
        $this->allCustomers = Customer::where('tenant_id', auth()->user()->tenant_id)->get();
        $this->cart = session()->get('pos_cart', []);
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (isset($this->cart[$productId])) 
        {
            $this->cart[$productId]['quantity']++;
        } 
        else 
        {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('pos_cart', $this->cart);
    }

    public function incrementQuantity($productId)
    {
        if (isset($this->cart[$productId])) 
        {
            $this->cart[$productId]['quantity']++;
            session()->put('pos_cart', $this->cart);
        }
    }

    public function decrementQuantity($productId)
    {
        if (isset($this->cart[$productId])) 
        {
            $this->cart[$productId]['quantity']--;

            if ($this->cart[$productId]['quantity'] < 1) 
            {
                $this->removeItem($productId);
            }
            
            session()->put('pos_cart', $this->cart);
        }
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);
        session()->put('pos_cart', $this->cart);
    }

    public function checkout(CreateOrderService $orderService)
    {
        // 1. Validate the cart and customer selection
        $this->validate([
            'cart' => 'required|array|min:1',
            'selectedCustomerId' => 'required|exists:customers,id'
        ], [
            'selectedCustomerId.required' => 'Please select a customer before checkout.'
        ]);

        try 
        {
            // 2. Call the service to handle the complex logic
            $order = $orderService->handle($this->cart, $this->selectedCustomerId, auth()->user());

            // 3. Clear the cart state after success
            $this->cart = [];
            session()->forget('pos_cart');

            // 4. Show success message and redirect
            session()->flash('success', 'Order created successfully! Order Number: ' . $order->order_number);
            return redirect()->route('store.pos.index');

        } 
        catch (\Exception $e) 
        {
            // 5. Catch any errors (e.g., insufficient stock) and show them to the user
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $products = Product::where('tenant_id', auth()->user()->tenant_id)
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(12);

        $cartSubtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('livewire.store.pos.pos-screen', [
            'products' => $products,
            'cartSubtotal' => $cartSubtotal,
        ])->layout('layouts.app', ['title' => 'Point of Sale (POS)']);
    }
}
