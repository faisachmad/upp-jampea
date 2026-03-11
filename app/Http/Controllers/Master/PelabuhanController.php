<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Pelabuhan;
use Illuminate\Http\Request;

class PelabuhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pelabuhan::query();

        // Search by kode or nama
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $pelabuhans = $query->orderBy('kode')->paginate(15);

        return view('master.pelabuhan.index', compact('pelabuhans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.pelabuhan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:pelabuhans,kode',
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:UPP,POSKER,WILKER,LUAR',
            'is_active' => 'boolean',
        ]);

        Pelabuhan::create($validated);

        return redirect()->route('master.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelabuhan $pelabuhan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelabuhan $pelabuhan)
    {
        return view('master.pelabuhan.edit', compact('pelabuhan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelabuhan $pelabuhan)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:pelabuhans,kode,' . $pelabuhan->id,
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:UPP,POSKER,WILKER,LUAR',
            'is_active' => 'boolean',
        ]);

        $pelabuhan->update($validated);

        return redirect()->route('master.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelabuhan $pelabuhan)
    {
        $pelabuhan->delete();

        return redirect()->route('master.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil dihapus.');
    }
}
