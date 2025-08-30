<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::latest()->paginate(10);

        return view('admin.tenants.index', [
            'tenants' => $tenants,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'domain' => 'nullable|string|max:255|unique:tenants,domain',
            'receipt_header_text' => 'nullable|string',
            'receipt_footer_text' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        Tenant::create($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenant = Tenant::find($id);

        return view('admin.tenants.edit', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenant::find($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'domain' => 'nullable|string|max:255|unique:tenants,domain,' . $tenant->id,
            'receipt_header_text' => 'nullable|string',
            'receipt_footer_text' => 'nullable|string',
        ]);
        
        $validated['updated_by'] = auth()->id();
        $tenant->update($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tenant = Tenant::find($id);
        $tenant->delete();

        return back()->with('success', 'Tenant and all related data deleted successfully.');
    }
}
