<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูลผู้ใช้ทั้งหมด เรียงจากล่าสุด และแบ่งหน้าแสดงผล (Paginate)
        $users = User::latest()->paginate(10);

        // ส่งข้อมูลไปยัง View
        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('name')->get();
        $tenants = Tenant::where('status', 'active')->get();

        return view('admin.users.create', [
            'roles' => $roles,
            'tenants' => $tenants,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' จะเช็คกับ field 'password_confirmation'
            'roles' => 'required|array',
            'tenant_id' => 'nullable|exists:tenants,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // สร้าง User ใหม่ พร้อมเข้ารหัส Password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tenant_id' => $validated['tenant_id'] ?? null,
            'branch_id' => $validated['branch_id'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // กำหนด Role ให้กับ User ที่เพิ่งสร้าง
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
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
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::select('name')->get();
        $tenants = Tenant::where('status', 'active')->get();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'tenants' => $tenants,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // nullable คือไม่บังคับกรอก
            'roles' => 'required|array',
            'tenant_id' => 'nullable|exists:tenants,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // อัปเดตข้อมูลพื้นฐาน
        $validated['updated_by'] = auth()->id();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tenant_id' => $validated['tenant_id'] ?? null,
            'branch_id' => $validated['branch_id'] ?? null,
            'updated_by' => $validated['updated_by'],
        ]);

        // ตรวจสอบว่ามีการกรอกรหัสผ่านใหม่เข้ามาหรือไม่
        if ($request->filled('password')) 
        {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // อัปเดต Role
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        // ป้องกันการลบ Super Admin (สมมติว่า ID 1 คือ Super Admin หลัก)
        if ($user->id === 1 || $user->hasRole('super-admin')) 
        {
            return back()->with('error', 'Cannot delete Super Admin user.');
        }

        // (แนะนำ) ป้องกันไม่ให้ User ลบตัวเอง
        if (auth()->id() === $user->id) 
        {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
