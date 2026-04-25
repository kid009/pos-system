<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\SalesChannel;
use Illuminate\Http\Request;

class SalesChannelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $salesChannels = SalesChannel::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master-data.sales-channel.index', compact('salesChannels', 'search'));
    }

    public function create()
    {
        return view('master-data.sales-channel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($validated) {
            SalesChannel::create($validated);
        }, 'เพิ่มช่องทางการขายเรียบร้อยแล้ว');
    }

    public function edit(SalesChannel $salesChannel)
    {
        return view('master-data.sales-channel.edit', compact('salesChannel'));
    }

    public function update(Request $request, SalesChannel $salesChannel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($salesChannel, $validated) {
            $salesChannel->update($validated);
        }, 'อัปเดตช่องทางการขายเรียบร้อยแล้ว');
    }

    public function destroy(SalesChannel $salesChannel)
    {
        return $this->executeSafely(function () use ($salesChannel) {
            $salesChannel->update(['is_active' => false]);
        }, 'ระงับการใช้งานช่องทางการขายเรียบร้อยแล้ว');
    }
}