<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kapal::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        $kapals = $query->orderBy('nama')->paginate(15);

        return view('master.kapal.index', compact('kapals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.kapal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'jenis' => 'nullable|in:KLM,KM,KMP,MV',
            'gt' => 'nullable|numeric|min:0',
            'dwt' => 'nullable|numeric|min:0',
            'panjang' => 'nullable|numeric|min:0',
            'tanda_selar' => 'nullable|string|max:50',
            'call_sign' => 'nullable|string|max:20',
            'tempat_kedudukan' => 'nullable|string|max:100',
            'bendera' => 'nullable|string|max:50',
            'pemilik_agen' => 'nullable|string|max:200',
            'is_active' => 'boolean',
        ]);

        Kapal::create($validated);

        return redirect()->route('master.kapal.index')
            ->with('success', 'Kapal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kapal $kapal)
    {
        return view('master.kapal.show', compact('kapal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kapal $kapal)
    {
        return view('master.kapal.edit', compact('kapal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kapal $kapal)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'jenis' => 'nullable|in:KLM,KM,KMP,MV',
            'gt' => 'nullable|numeric|min:0',
            'dwt' => 'nullable|numeric|min:0',
            'panjang' => 'nullable|numeric|min:0',
            'tanda_selar' => 'nullable|string|max:50',
            'call_sign' => 'nullable|string|max:20',
            'tempat_kedudukan' => 'nullable|string|max:100',
            'bendera' => 'nullable|string|max:50',
            'pemilik_agen' => 'nullable|string|max:200',
            'is_active' => 'boolean',
        ]);

        $kapal->update($validated);

        return redirect()->route('master.kapal.index')
            ->with('success', 'Kapal berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kapal $kapal)
    {
        $kapal->delete();

        return redirect()->route('master.kapal.index')
            ->with('success', 'Kapal berhasil dihapus.');
    }
}
