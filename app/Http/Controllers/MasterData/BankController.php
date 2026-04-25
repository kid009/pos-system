<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $banks = Bank::when($search, function ($q, $search) {
                return $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('master-data.bank.index', [
            'banks' => $banks,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('master-data.bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:banks,code',
            'account_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:50',
        ]);

        Bank::create([
            'name' => $request->name,
            'code' => $request->code,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('banks.index')->with('success', 'เพิ่มธนาคารเรียบร้อยแล้ว');
    }

    public function edit(string $id)
    {
        $bank = Bank::findOrFail($id);

        return view('master-data.bank.edit', [
            'bank' => $bank,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $bank = Bank::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:banks,code,' . $bank->id,
            'account_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:50',
        ]);

        $bank->update([
            'name' => $request->name,
            'code' => $request->code,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('banks.index')->with('success', 'อัปเดตธนาคารเรียบร้อยแล้ว');
    }

    public function destroy(string $id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'ลบธนาคารเรียบร้อยแล้ว');
    }
}