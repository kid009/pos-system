<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Manage Products')]
class ProductComponent extends Component
{
    use WithPagination;

    public $productId;
    public $category_id, $name, $cost, $price, $stock_qty;
    public $isOpen = false;
    public $search = '';

    public function create()
    {
        $this->reset(['category_id', 'name', 'cost', 'price', 'stock_qty', 'productId']);
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0', // ขายต่ำกว่าทุนได้ไหม? ถ้าไม่ได้ต้องเช็ค gt:cost
            'stock_qty' => 'required|integer|min:0',
        ]);

        Product::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'stock_qty' => $this->stock_qty,
        ]);

        $this->isOpen = false;

        $this->dispatch('notify', message: 'Product created successfully.', type: 'success');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock_qty = $product->stock_qty;

        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'stock_qty' => 'required|integer',
        ]);

        $product = Product::findOrFail($this->productId);
        $product->update([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
            'stock_qty' => $this->stock_qty,
        ]);

        $this->isOpen = false;

        $this->dispatch('notify', message: 'Product updated successfully.', type: 'success');
    }

    public function delete($id)
    {
        Product::find($id)->delete();

        $this->dispatch('notify', message: 'Product deleted.', type: 'error');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function render()
    {
        // Senior Tip: ใช้ with('category') เพื่อแก้ปัญหา N+1 Query
        $products = Product::with('category')
                    ->where('name', 'like', '%'.$this->search.'%')
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        return view('livewire.admin.product-component', [
            'products' => $products,
            'categories' => Category::all(), // ส่งไปให้ Dropdown
        ]);
    }
}
