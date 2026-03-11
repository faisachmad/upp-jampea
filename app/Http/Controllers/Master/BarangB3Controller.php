<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\BarangB3;
use Illuminate\Http\Request;

class BarangB3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BarangB3::query();

        // Search by nama or un_number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'ILIKE', '%' . $search . '%')
                  ->orWhere('un_number', 'ILIKE', '%' . $search . '%');
            });
        }

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $barangB3s = $query->orderBy('nama')->paginate(15);

        return view('master.barang-b3.index', compact('barangB3s'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.barang-b3.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'un_number' => 'required|string|max:10',
            'kelas' => 'required|string|max:10',
            'kategori' => 'nullable|string|max:50',
        ]);

        BarangB3::create($validated);

        return redirect()->route('master.barang-b3.index')
            ->with('success', 'Data barang B3 berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangB3 $barangB3)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangB3 $barangB3)
    {
        return view('master.barang-b3.edit', compact('barangB3'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangB3 $barangB3)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'un_number' => 'required|string|max:10',
            'kelas' => 'required|string|max:10',
            'kategori' => 'nullable|string|max:50',
        ]);

        $barangB3->update($validated);

        return redirect()->route('master.barang-b3.index')
            ->with('success', 'Data barang B3 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangB3 $barangB3)
    {
        $barangB3->delete();

        return redirect()->route('master.barang-b3.index')
            ->with('success', 'Data barang B3 berhasil dihapus.');
    }
}
