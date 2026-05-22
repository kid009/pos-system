<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Http\Requests\MasterData\Warehouse\WarehouseRequest;
use App\Actions\Inventory\CreateWarehouseAction;
use App\Actions\Inventory\UpdateWarehouseAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\QueryException;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $warehouses = Warehouse::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('code')
            ->paginate(15)
            ->withQueryString();

        return view('master-data.inventory.warehouses.index', [
            'warehouses' => $warehouses,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('master-data.inventory.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request, CreateWarehouseAction $action): RedirectResponse
    {
        $action->execute($request->toDTO());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse): View
    {
        return view('master-data.inventory.warehouses.edit', [
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse, UpdateWarehouseAction $action): RedirectResponse
    {
        $action->execute($warehouse, $request->toDTO());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        try {
            // Hard delete attempt guarded strictly by database relational integrity constraint rules
            $warehouse->delete();
            return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
        } catch (QueryException $e) {
            // 23000 is MySQL state token for integrity constraint violations (FK restrictions)
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'a foreign key constraint fails')) {
                return back()->with('error', 'Cannot delete warehouse. This location contains operational historical stock transaction logs.');
            }
            return back()->with('error', 'An unexpected database error occurred.');
        }
    }
}
