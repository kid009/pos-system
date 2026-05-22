<?php

namespace App\Http\Controllers\MasterData;

use App\Actions\MasterData\Inventory\RecordStockInboundAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreInboundStockRequest;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StockInboundController extends Controller
{
    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('master-data.inventory.inbound.create', [
            'products' => $products,
            'warehouses' => $warehouses,
        ]);
    }

    public function store(StoreInboundStockRequest $request, RecordStockInboundAction $action)
    {
        try {
            $action->execute($request->toDTO());

            return redirect()->route('product.index')->with('success', 'Stock inbound recorded and product inventory updated successfully.');
        } catch (\Exception $e) {
            // Audit anomaly silently into server logs
            Log::error('Stock Inbound Anomaly detected: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'An internal error occurred. Failed to record stock inbound.');
        }
    }
}
