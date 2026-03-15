<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TipePelabuhan;
use Illuminate\Http\Request;

class TipePelabuhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipes = TipePelabuhan::withCount('pelabuhans')->orderBy('nama')->get();
        return view('master.tipe-pelabuhan.index', compact('tipes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:tipe_pelabuhans,nama',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tipe = TipePelabuhan::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe pelabuhan berhasil ditambahkan.',
                'data' => $tipe
            ]);
        }

        return redirect()->route('master.tipe-pelabuhan.index')
            ->with('success', 'Tipe pelabuhan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipePelabuhan $tipePelabuhan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:tipe_pelabuhans,nama,' . $tipePelabuhan->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tipePelabuhan->update($validated);

        return redirect()->route('master.tipe-pelabuhan.index')
            ->with('success', 'Tipe pelabuhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipePelabuhan $tipePelabuhan)
    {
        // Check if tipe pelabuhan is being used
        if ($tipePelabuhan->pelabuhans()->count() > 0) {
            return back()->with('error', 'Tipe pelabuhan tidak dapat dihapus karena masih digunakan.');
        }

        $tipePelabuhan->delete();

        return redirect()->route('master.tipe-pelabuhan.index')
            ->with('success', 'Tipe pelabuhan berhasil dihapus.');
    }
}
