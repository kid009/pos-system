<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูลสาขา พร้อมกับข้อมูล Tenant ที่เกี่ยวข้องมาด้วย (Eager Loading)
        $branches = Branch::with('tenant')->latest()->paginate(10);

        return view('admin.branches.index', [
            'branches' => $branches
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = Tenant::where('status', 'active')->get(); // ดึง Tenant ทั้งหมดมาให้เลือก
        return view('admin.branches.create', [
            'tenants' => $tenants
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'required|exists:tenants,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();
        Branch::create($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $branch = Branch::find($id);
        $tenants = Tenant::where('status', 'active')->get();

        return view('admin.branches.edit', [
            'branch' => $branch,
            'tenants' => $tenants
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $branch = Branch::find($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'required|exists:tenants,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
        ]);
        
        $validated['updated_by'] = auth()->id();
        $branch->update($validated);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $branch = Branch::find($id);
        $branch->delete();

        return back()->with('success', 'Branch deleted successfully.');
    }

    public function getBranchesByTenant($tenantId)
    {
        $branches = Branch::where('tenant_id', $tenantId)->get();
        return response()->json($branches);
    }
}
